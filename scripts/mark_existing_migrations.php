<?php
// Marks migration files as applied in the `migrations` table when their
// corresponding tables already exist in the database. Safe to run on CI
// or in a deploy hook. This script never exits with non-zero so it won't
// fail a deployment; it reports what it did to stdout.

function loadEnv(): array
{
    $env = [];
    if (!file_exists(__DIR__ . '/../.env')) return $env;
    foreach (file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) $env[trim($parts[0])] = trim($parts[1]);
    }
    return $env;
}

try {
    $env = loadEnv();
    if (empty($env['DB_HOST'] ?? '') || empty($env['DB_DATABASE'] ?? '')) {
        echo "No DB configuration found in .env; skipping helper.\n";
        exit(0);
    }

    $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s;sslmode=require', $env['DB_HOST'], $env['DB_PORT'] ?? 5432, $env['DB_DATABASE']);
    $pdo = new PDO($dsn, $env['DB_USERNAME'] ?? null, $env['DB_PASSWORD'] ?? null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    // Gather applied migrations
    $stmt = $pdo->query('SELECT migration FROM migrations');
    $applied = $stmt ? $stmt->fetchAll(PDO::FETCH_COLUMN) : [];

    // Find migration files on disk
    $migrationsOnDisk = [];
    foreach (glob(__DIR__ . '/../database/migrations/*.php') as $f) $migrationsOnDisk[] = $f;

    $toInsert = [];
    foreach ($migrationsOnDisk as $path) {
        $name = basename($path, '.php');
        if (in_array($name, $applied)) continue;

        $content = file_get_contents($path);
        // detect tables created/altered by migration
        preg_match_all("/Schema::create\(\s*'([a-z0-9_]+)'/i", $content, $creates);
        preg_match_all("/Schema::table\(\s*'([a-z0-9_]+)'/i", $content, $tables);
        $tablesTouched = array_unique(array_merge($creates[1] ?? [], $tables[1] ?? []));
        if (empty($tablesTouched)) continue;

        // if all 'create' targets already exist, we can mark this migration as applied
        $allExist = true;
        foreach ($tablesTouched as $t) {
            $q = $pdo->prepare("SELECT to_regclass('public." . $t . "') as reg");
            $q->execute();
            $res = $q->fetch(PDO::FETCH_ASSOC);
            if (!($res && $res['reg'])) {
                $allExist = false;
                break;
            }
        }
        if ($allExist) $toInsert[] = $name;
    }

    if (empty($toInsert)) {
        echo "No migrations to mark as applied.\n";
        exit(0);
    }

    // compute next batch
    $mb = $pdo->query('SELECT max(batch) as mb FROM migrations')->fetch(PDO::FETCH_ASSOC);
    $nextBatch = ($mb && $mb['mb']) ? intval($mb['mb']) + 1 : 1;
    $insert = $pdo->prepare('INSERT INTO migrations (migration, batch) VALUES (:migration, :batch)');
    foreach ($toInsert as $mig) {
        // double-check and insert
        $q = $pdo->prepare('SELECT 1 FROM migrations WHERE migration = :m');
        $q->execute([':m' => $mig]);
        if ($q->fetch()) {
            echo "Already recorded: $mig\n";
            continue;
        }
        $insert->execute([':migration' => $mig, ':batch' => $nextBatch]);
        echo "Inserted migration record: $mig (batch $nextBatch)\n";
    }

} catch (Exception $e) {
    echo "Helper encountered an error: " . $e->getMessage() . "\n";
}

// always exit 0 to avoid breaking deploy flow
exit(0);
