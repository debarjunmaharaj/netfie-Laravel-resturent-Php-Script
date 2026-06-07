<?php

use App\Http\Controllers\CafeController;
use Illuminate\Support\Facades\Route;

// --- PUBLIC ROUTES ---
Route::get('/', [CafeController::class, 'home'])->name('home');
Route::post('/online-order', [CafeController::class, 'placeOnlineOrder'])->name('online-order.store');

// --- AUTH GUEST ROUTE ---
Route::get('/login', [CafeController::class, 'showLogin'])->name('login');
Route::post('/login', [CafeController::class, 'login']);
Route::post('/register', [CafeController::class, 'register'])->name('register');
Route::post('/logout', [CafeController::class, 'logout'])->name('logout');

// --- PROTECTED STAFF ROUTE GROUP (Standard Auth) ---
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [CafeController::class, 'dashboard'])->name('dashboard');
    
    // POS Terminal
    Route::get('/pos', [CafeController::class, 'pos'])->name('pos');
    Route::post('/pos', [CafeController::class, 'placePosOrder'])->name('pos.store');
    
    // Live Orders / Kitchen Screen
    Route::get('/orders', [CafeController::class, 'orders'])->name('orders');
    Route::post('/orders/{order}/status', [CafeController::class, 'updateOrderStatus'])->name('orders.status');
    
    // Menu CRUD
    Route::get('/menu', [CafeController::class, 'menu'])->name('menu');
    Route::post('/menu/category', [CafeController::class, 'storeCategory'])->name('menu.category.store');
    Route::post('/menu/category/{category}', [CafeController::class, 'updateCategory'])->name('menu.category.update');
    Route::post('/menu/category/{category}/delete', [CafeController::class, 'deleteCategory'])->name('menu.category.delete');
    
    Route::post('/menu/product', [CafeController::class, 'storeProduct'])->name('menu.product.store');
    Route::post('/menu/product/{product}', [CafeController::class, 'updateProduct'])->name('menu.product.update');
    Route::post('/menu/product/{product}/delete', [CafeController::class, 'deleteProduct'])->name('menu.product.delete');
});
