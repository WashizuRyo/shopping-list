<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShoppingListController;
use App\Http\Controllers\Api\ItemController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('/shopping_lists', [ShoppingListController::class, 'index'])->name('shopping_lists.index');
    Route::post('/shopping_lists', [ShoppingListController::class, 'store'])
        ->name('shopping_lists.store');
    Route::get('/shopping_lists/{shoppingList}', [ShoppingListController::class, 'show'])
        ->name('shopping_lists.show');
    Route::get('/shopping_lists/{shoppingList}/edit', [ShoppingListController::class, 'edit'])
        ->name('shopping_lists.edit');
    Route::put('/shopping_lists/{shoppingList}', [ShoppingListController::class, 'update'])
        ->name('shopping_lists.update');
    Route::delete('/shopping_lists/{shoppingList}', [ShoppingListController::class, 'destroy'])
        ->name('shopping_lists.destroy');

    Route::resource('items', ItemController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
