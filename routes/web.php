<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainControllerHome;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MenuControllerHome;
use App\Http\Controllers\ProductControllerHome;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\AccountControllerHome;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ForgotPasswordController;

#Menu
Route::middleware(['auth', 'checkAdmin'])->group(function () {  // nhóm các route (đường dẫn) lại với nhau và áp dụng middleware auth cho tất cả các route trong nhóm đó
    Route::prefix('admin')->group(function () {
        Route::get('/', [MainController::class, 'index'])->name('admin');
        Route::get('main',[MainController::class, 'index'])->name('index');
        Route::prefix('menus')->group(function () {
            Route::get('add', [MenuController::class, 'create'])->name('admin.menu.create');
            Route::post('add', [MenuController::class, 'store'])->name('admin.menu.store');
            Route::get('list', [MenuController::class, 'index'])->name('admin.menu.list');
            Route::get('edit/{menu}', [MenuController::class, 'show'])->name('admin.menu.show');
            Route::post('edit/{menu}', [MenuController::class, 'update'])->name('admin.menu.update');
            Route::DELETE('destroy', [MenuController::class, 'destroy'])->name('admin.menu.destroy');
        });
        #products
        Route::prefix('products')->group(function () {
            Route::get('add', [ProductController::class, 'create']);
            Route::post('add', [ProductController::class, 'store']);
            Route::get('list', [ProductController::class, 'index']);
            Route::get('edit/{product}', [ProductController::class, 'show']);
            Route::post('edit/{product}', [ProductController::class, 'update']);
            Route::DELETE('destroy', [ProductController::class, 'destroy']);
        });

        #upload
        Route::post('upload/services', [UploadController::class, 'store']);

        #sliders
        Route::prefix('sliders')->group(function () {
            Route::get('add', [SliderController::class, 'create']);
            Route::post('add', [SliderController::class, 'store']);
            Route::get('list', [SliderController::class, 'index']);
            Route::get('edit/{slider}', [SliderController::class, 'show']);
            Route::post('edit/{slider}', [SliderController::class, 'update']);
            Route::DELETE('destroy', [SliderController::class, 'destroy']);
        });

        Route::prefix('orders')->group(function () {
            Route::get('list', [OrderController::class, 'index']);
            Route::get('detail/{order}/{date}', [OrderController::class, 'show']);
            Route::post('/order/submit/{order}/{date}', [OrderController::class, 'shipping'])->name('order.shipping');
            Route::post('/order/cancel/{order}/{date}', [OrderController::class, 'cancel'])->name('order.cancel');
            Route::post('/order/complete/{order}/{date}', [OrderController::class, 'complete'])->name('order.complete');
        });

        Route::prefix('accounts')->group(function () {
            Route::get('list', [UserController::class, 'index']);
            Route::get('edit/{account}', [UserController::class, 'show']);
            Route::post('/user/{id}/update', [UserController::class, 'update'])->name('user.update');
            Route::post('/user/{id}/change-password', [UserController::class, 'changePassword'])->name('user.changePassword');
        });
    });

});

Route::get('users/login', [\App\Http\Controllers\LoginController::class, 'index'])->name('login');
Route::post('users/login/store', [\App\Http\Controllers\LoginController::class, 'store'])->name('store');
Route::post('users/logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logout');
Route::get('users/signup', [\App\Http\Controllers\LoginController::class, 'register'])->name('register');
Route::post('users/signup/store', [\App\Http\Controllers\LoginController::class, 'postregister'])->name('postregister');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot.password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'handleForgotPassword']);
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password');
Route::post('/reset-password', [ForgotPasswordController::class, 'handleResetPassword']);

Route::middleware(['auth'])->group(function () {
    Route::get('users/account/{id}', [AccountControllerHome::class, 'index'])->name('profile');
    Route::get('users/account/{id}/order/{date}', [AccountControllerHome::class, 'order'])->name('order');
    Route::post('users/account/{id}/order/{date}', [AccountControllerHome::class, 'cancel'])->name('account.order.cancel');
    Route::get('users/account/settings/{id}', [AccountControllerHome::class, 'setting'])->name('setting');
    Route::post('users/account/settings/{id}', [AccountControllerHome::class, 'update'])->name('update');
});
Route::post('users/account/settings/{id}/password-reset', [AccountControllerHome::class, 'changePassword'])->name('setting.change-password');
Route::middleware(['auth'])->group(function () {
    Route::get('users/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('users/wishlist/add/{product}', [WishlistController::class, 'store'])->name('wishlist.add');
    Route::delete('users/wishlist/remove/{product}', [WishlistController::class, 'destroy'])->name('wishlist.remove');
});



Route::get('/', [MainControllerHome::class, 'index'])->name('home');
Route::get('/search', [MainControllerHome::class, 'search']);
Route::get('/search-money', [MainControllerHome::class, 'searchMoney']);
Route::post('/services/load-products', [MainControllerHome::class, 'loadProducts']);
Route::get('danh-muc/{id}-{parent_id}-{slug}.html', [MenuControllerHome::class, 'index']);
Route::get('san-pham/{id}-{slug}.html', [ProductControllerHome::class, 'index']);
Route::post('add-to-cart', [CartController::class, 'index']);
Route::get('carts', [CartController::class, 'show']);
Route::post('update-cart', [CartController::class, 'update']);
Route::get('/remove-cart/{id}', [CartController::class, 'remove']);
Route::post('carts', [CartController::class, 'addCarts']);
