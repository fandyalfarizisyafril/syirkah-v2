<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::view('/tentang-kami', 'public.about')->name('about');
Route::get('/produk', [PublicController::class, 'products'])->name('products.index');
Route::get('/produk/{category}', [PublicController::class, 'products'])->name('products.category');
Route::get('/produk/{category}/{product}', [PublicController::class, 'product'])->name('products.show');
Route::get('/brand', [PublicController::class, 'directory'])->name('brands.index');
Route::get('/brand/{slug}', [PublicController::class, 'directory'])->name('brands.show');
Route::get('/industri', [PublicController::class, 'directory'])->name('industries.index');
Route::get('/industri/{slug}', [PublicController::class, 'directory'])->name('industries.show');
Route::get('/kontak', [PublicController::class, 'contact'])->name('contact');
Route::post('/inquiry', [InquiryController::class, 'store'])->middleware('throttle:inquiry')->name('inquiry.store');
Route::get('/search', [PublicController::class, 'search'])->name('search');
Route::view('/privasi', 'public.privacy')->name('privacy');
Route::get('/sitemap.xml', [PublicController::class, 'sitemap'])->name('sitemap');

Route::prefix('admin')->group(function () {
    Route::view('/login', 'admin.login')->middleware('guest')->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware(['guest', 'throttle:login'])->name('login.store');
    Route::middleware(['auth', 'admin'])->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::view('/account', 'admin.account')->name('account');
        Route::put('/account', [AuthController::class, 'updatePassword'])->middleware('throttle:login')->name('account.update');
        Route::middleware('can:manage-inquiries')->group(function () {
            Route::get('/inquiries', [DashboardController::class, 'inquiries'])->name('inquiries.index');
            Route::get('/inquiries/{inquiry}', [DashboardController::class, 'inquiry'])->name('inquiries.show');
            Route::put('/inquiries/{inquiry}', [DashboardController::class, 'updateInquiry'])->name('inquiries.update');
        });
        Route::middleware('can:manage-settings')->group(function () {
            Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
            Route::put('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');
        });
        Route::prefix('hero')->middleware('can:manage-content')->name('hero.')->group(function () {
            Route::get('/', [HeroController::class, 'index'])->name('index');
            Route::get('/{focus}/edit', [HeroController::class, 'edit'])->name('edit');
            Route::put('/{focus}', [HeroController::class, 'update'])->name('update');
        });
        Route::prefix('catalog/{module}')->where(['module' => 'categories|brands|products|industries'])->middleware('can:manage-content')->name('catalog.')->group(function () {
            Route::get('/', [CatalogController::class, 'index'])->name('index');
            Route::get('/create', [CatalogController::class, 'form'])->name('create');
            Route::post('/', [CatalogController::class, 'save'])->name('store');
            Route::get('/{id}/edit', [CatalogController::class, 'form'])->whereNumber('id')->name('edit');
            Route::put('/{id}', [CatalogController::class, 'save'])->whereNumber('id')->name('update');
            Route::delete('/{id}', [CatalogController::class, 'destroy'])->whereNumber('id')->name('destroy');
        });
    });
});
