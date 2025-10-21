<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\SocialController;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Admin\SettingsController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {

        // Admin Dashboard
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

            // Products
            Route::prefix('products')->group(function () {
                Route::get('/', [AdminProductController::class, 'index'])->name('products.index');
                Route::get('create', [AdminProductController::class, 'create'])->name('products.create');
                Route::post('store', [AdminProductController::class, 'store'])->name('products.store');
                Route::get('{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
                Route::put('{product}', [AdminProductController::class, 'update'])->name('products.update');
                Route::delete('{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
            });
            // Categories
            Route::prefix('categories')->group(function () {
                Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
                Route::get('create', [CategoryController::class, 'create'])->name('categories.create');
                Route::post('store', [CategoryController::class, 'store'])->name('categories.store');
                Route::get('{id}/children', [CategoryController::class, 'getChildrenById'])->name('categories.children');
                Route::get('{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
                Route::put('{category}', [CategoryController::class, 'update'])->name('categories.update');
                Route::delete('{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
            });
            // Attributes
            Route::prefix('attributes')->group(function () {
                Route::get('/', [AttributeController::class, 'index'])->name('attributes.index');
                Route::get('create', [AttributeController::class, 'create'])->name('attributes.create');
                Route::post('store', [AttributeController::class, 'store'])->name('attributes.store');
                Route::get('{attribute}/edit', [AttributeController::class, 'edit'])->name('attributes.edit');
                Route::put('{attribute}', [AttributeController::class, 'update'])->name('attributes.update');
                Route::delete('{attribute}', [AttributeController::class, 'destroy'])->name('attributes.destroy');
                Route::get('{id}/move-up', [AttributeController::class, 'moveUp'])->name('attributes.move_up');
                Route::get('{id}/move-down', [AttributeController::class, 'moveDown'])->name('attributes.move_down');
                Route::post('reorder', [AttributeController::class, 'reorder'])->name('attributes.reorder');
            });
            // Stock / Inventory
            Route::prefix('inventory')->group(function () {
                Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
                Route::get('bulk-edit', [InventoryController::class, 'bulkEdit'])->name('inventory.bulk-edit');
                Route::post('bulk-update', [InventoryController::class, 'bulkUpdate'])->name('inventory.bulk-update');
                Route::get('{product}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
                Route::put('{product}', [InventoryController::class, 'update'])->name('inventory.update');
        });
            // Orders
            Route::prefix('orders')->group(function () {
                Route::get('/', [OrderController::class, 'index'])->name('orders.index');
                Route::get('{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
                Route::put('{order}', [OrderController::class, 'update'])->name('orders.update');
                Route::patch('{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
                Route::delete('{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
                // Route::get('{order}/history', [OrderController::class, 'history'])->name('orders.history');
            });


            // Store Configuration
            // Store Settings
            // Store Identity
            Route::prefix('settings')->group(function () {
                Route::get('/', [SettingsController::class, 'storeIdentity'])->name('identity.index');
                Route::put('/update', [SettingsController::class, 'updateStoreIdentity'])->name('identity.update');
            });


});


// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route::get('/', function () {
//     return view('auth.login');
// });


Route::get('/category/{slug}', [FrontendCategoryController::class, 'show'])
    ->name('category.show');




Route::get('register', [RegisterController::class, 'showForm']);
Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.update');
// Social Login
Route::get('auth/{provider}', [SocialController::class, 'redirect'])
    ->name('social.redirect');

Route::get('auth/{provider}/callback', [SocialController::class, 'callback'])
    ->name('social.callback');
