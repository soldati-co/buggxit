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
    $ipUrl = env('NIGHTWATCH_INGEST_URL', 'https://ingest.nightwatch.io');
    
    try {
        $client = new \GuzzleHttp\Client(['timeout' => 5]);
        $res = $client->post($ipUrl . '/api/v1/logs', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('NIGHTWATCH_TOKEN'),
                'Content-Type'  => 'application/json',
            ],
            'json' => ['message' => 'Direct IP test', 'level' => 'info'],
        ]);
        $result = 'Status: ' . $res->getStatusCode();
    } catch (\Exception $e) {
        $result = 'FAILED: ' . $e->getMessage();
    }

    return response()->json([
        'nightwatch_ingest_url' => $ipUrl,
        'http_result'          => $result,
    ]);
});