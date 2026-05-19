<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ContactController;


// Load authentication routes
require __DIR__.'/auth.php'; // Customer auth
require __DIR__.'/admin.php'; // Admin routes

// ================== PUBLIC ROUTES ================== //

// Static pages
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

// Cart routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
});

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::get('/new-arrivals', [ProductController::class, 'newArrivals'])->name('newarrival');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{dress}', [ProductController::class, 'show'])->name('show');
});

Route::prefix('checkout')->name('checkout.')->middleware('web')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Search (make sure SearchController exists)
// Route::get('/search', [SearchController::class, 'index'])->name('search');

// ================== END PUBLIC ROUTES ================== //

Route::get('/nightwatch-test', function () {
    // 1. Show DNS environment variable
    $dnsEnv = env('DNS_SERVER', 'not set');

    // 2. Show contents of /etc/resolv.conf (where DNS servers are listed)
    $resolvConf = file_get_contents('/etc/resolv.conf');

    // 3. Try resolving the Nightwatch ingest hostname using PHP
    $nightwatchHost = 'ingest.nightwatch.io';
    $resolvedIp = gethostbyname($nightwatchHost);
    $dnsWorks = ($resolvedIp !== $nightwatchHost) ? true : false;

    // 4. HTTP ping test (same as before)
    $httpTest = 'skipped';
    if ($dnsWorks) {
        try {
            $client = new \GuzzleHttp\Client(['timeout' => 5]);
            $res = $client->post('https://' . $nightwatchHost . '/api/v1/logs', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('NIGHTWATCH_TOKEN'),
                    'Content-Type'  => 'application/json',
                ],
                'json' => ['message' => 'HTTP diagnostic', 'level' => 'info'],
            ]);
            $httpTest = 'Status: ' . $res->getStatusCode();
        } catch (\Exception $e) {
            $httpTest = 'FAILED: ' . $e->getMessage();
        }
    } else {
        $httpTest = 'SKIPPED (DNS resolution failed)';
    }

    return response()->json([
        'dns_server_env' => $dnsEnv,
        'resolv_conf'    => $resolvConf,
        'resolved_ip'    => $resolvedIp,
        'dns_works'      => $dnsWorks,
        'http_ping_test' => $httpTest,
    ]);
});