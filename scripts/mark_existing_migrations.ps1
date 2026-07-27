#!/usr/bin/env pwsh
Write-Output "Running PHP migration helper (mark_existing_migrations.php)"
$php = Get-Command php -ErrorAction SilentlyContinue
if (-not $php) {
    Write-Error "PHP is not available in PATH. Install PHP or add it to PATH to run this helper."
    exit 0
}

# Run the PHP helper script (safe; exits 0)
& php "$(Split-Path -Parent $MyInvocation.MyCommand.Path)\mark_existing_migrations.php"
Write-Output "Done."
