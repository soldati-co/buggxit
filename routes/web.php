<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\CheckoutApiController;
use App\Http\Controllers\Api\ContactApiController;
use App\Http\Controllers\Api\DressApiController;
use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\ImageController;

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';

// ================== PUBLIC ROUTES ================== //

Route::get('/about', function () { return view('pages.about'); })->name('about');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
});

Route::get('/contact', function () { return view('pages.contact'); })->name('contact');

Route::get('/new-arrivals', [ProductController::class, 'newArrivals'])->name('newarrival');
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{dress}', [ProductController::class, 'show'])->name('show');
});

Route::prefix('checkout')->name('checkout.')->middleware('web')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/nightwatch-test', function () {
    \Illuminate\Support\Facades\Log::info('Nightwatch test log via agent');
    return response()->json(['message' => 'Logged. Check Nightwatch dashboard.']);
});

// ================== API ROUTES ================== //
// Route::get('/api/hero-slides/{id}/image', [HeroSlideController::class, 'getImage'])->name('api.hero-slides.image');
// Route::get('/api/dresses/{id}/image', [App\Http\Controllers\Admin\AdminDressController::class, 'getImage'])->name('api.dresses.image');

Route::get('/images/{id}', [ImageController::class, 'show'])->name('api.image.show');

Route::prefix('api')->name('api.')->middleware(['web', 'throttle:60,1'])->group(function () {
    Route::get('/home', [HomeApiController::class, 'index'])->name('home.index');

    Route::get('/products', [ProductApiController::class, 'index'])->name('products.index');
    Route::get('/products/new-arrivals', [ProductApiController::class, 'newArrivals'])->name('products.new-arrivals');
    Route::get('/products/{dress}', [ProductApiController::class, 'show'])->name('products.show');

    Route::get('/cart', [CartApiController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartApiController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartApiController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartApiController::class, 'remove'])->name('cart.remove');

    Route::post('/checkout', [CheckoutApiController::class, 'store'])->name('checkout.store');
    Route::post('/contact', [ContactApiController::class, 'submit'])->name('contact.submit')->middleware('throttle:10,1');

    Route::get('/orders', [OrderApiController::class, 'index'])->name('orders.index')->middleware('auth');
    Route::get('/orders/{order}', [OrderApiController::class, 'show'])->name('orders.show')->middleware('auth');

    Route::get('/dresses', [DressApiController::class, 'index'])->name('dresses.index');
    Route::get('/dresses/{dress}', [DressApiController::class, 'show'])->name('dresses.show');
    Route::post('/dresses', [DressApiController::class, 'store'])->name('dresses.store');
    Route::put('/dresses/{dress}', [DressApiController::class, 'update'])->name('dresses.update');
    Route::delete('/dresses/{dress}', [DressApiController::class, 'destroy'])->name('dresses.destroy');
});