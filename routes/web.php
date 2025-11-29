<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistoryController;
// Controllers
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockMovementController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/use App\Http\Controllers\ManagerNotificationController;

// routes/web.php (inside auth + manager middleware)
Route::prefix('stock')->name('stock.')->middleware(['auth','role:manager'])->group(function () {
    Route::resource('items', \App\Http\Controllers\StockItemController::class)->except(['show']);
    Route::get('purchases/create', [\App\Http\Controllers\StockPurchaseController::class,'create'])->name('purchases.create');
    Route::post('purchases', [\App\Http\Controllers\StockPurchaseController::class,'store'])->name('purchases.store');
    Route::get('adjustments/create', [\App\Http\Controllers\StockAdjustmentController::class,'create'])->name('adjustments.create');
    Route::post('adjustments', [\App\Http\Controllers\StockAdjustmentController::class,'store'])->name('adjustments.store');
    Route::get('movements', [\App\Http\Controllers\StockMovementController::class,'index'])->name('movements.index');
});

// …
Route::middleware(['auth','role:manager'])->group(function () {
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stock/{stock}/adjust', [StockController::class, 'adjust'])->name('stock.adjust');
    Route::get('/stock/movements', [StockMovementController::class, 'index'])->name('stock.movements');
});

Route::get('/manager/notifications', [ManagerNotificationController::class, 'index'])
    ->middleware('role:manager')
    ->name('manager.notifications.index');
Route::get('/kitchen', [\App\Http\Controllers\KitchenController::class, 'index'])
    ->middleware('role:kitchen|bar|manager')
    ->name('kitchen.index');

Route::patch('/orders/{order}/status', [\App\Http\Controllers\KitchenController::class, 'status'])
    ->middleware('role:kitchen|bar|manager')
    ->name('orders.status');

Route::get('/manager/notifications/{id}/read', [ManagerNotificationController::class, 'read'])
    ->middleware('role:manager')
    ->name('manager.notifications.read');

Route::get('/history',                 [HistoryController::class,'month'])->name('history.month');          // current month
Route::get('/history/{year}',          [HistoryController::class,'year'])->name('history.year');            // year summary
Route::get('/history/{year}/{month}',  [HistoryController::class,'month'])->name('history.month.view');     // month calendar
Route::get('/history/{year}/{month}/{day}', [HistoryController::class,'day'])->name('history.day'); 
// Redirect root to dashboard
Route::get('/', fn () => redirect()->route('dashboard'));

// ===== Protected (must be logged in) =====
Route::middleware(['auth'])->group(function () {

    /* ---------- Dashboard (role-based) ---------- */
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole('manager')) {
            return redirect()->route('manager.index');
        }
        if ($user->hasRole('waiter')) {
            return redirect()->route('orders.create');
        }
        if ($user->hasRole('kitchen') || $user->hasRole('bar')) {
            return redirect()->route('kitchen.index');
        }
        abort(403);
    })->name('dashboard');

    /* ---------- Manager dashboard ---------- */
    Route::get('/manager', [ManagerController::class, 'index'])
        ->middleware('role:manager')
        ->name('manager.index');

    /* ---------- Kitchen / Bar ---------- */
    Route::get('/kitchen', [KitchenController::class, 'index'])
        ->middleware('role:kitchen|bar|manager')
        ->name('kitchen.index');

    Route::patch('/orders/{order}/status', [KitchenController::class, 'status'])
        ->middleware('role:kitchen|bar|manager')
        ->name('orders.status');

    /* ---------- Orders (manager|waiter) ---------- */
    Route::get('/orders', [OrderController::class, 'index'])
        ->middleware('role:manager|waiter')
        ->name('orders.index');

    Route::get('/orders/create', [OrderController::class, 'create'])
        ->middleware('role:waiter|manager')
        ->name('orders.create');

    Route::post('/orders', [OrderController::class, 'store'])
        ->middleware('role:waiter|manager')
        ->name('orders.store');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->middleware('role:manager|waiter')
        ->name('orders.show');

    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])
        ->middleware('role:manager|waiter')
        ->name('orders.edit');

    Route::patch('/orders/{order}', [OrderController::class, 'update'])
        ->middleware('role:manager|waiter')
        ->name('orders.update');

    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])
        ->middleware('role:manager|waiter')
        ->name('orders.destroy');

    /* ---------- Menu & Categories (manager only) ---------- */
    Route::resource('menu', MenuController::class)
        ->middleware('role:manager');               // names: menu.index, menu.create, ...

    Route::resource('categories', CategoryController::class)
        ->middleware('role:manager');               // names: categories.index, ...

    /* ---------- Comments / Notes (manager only) ---------- */
    Route::resource('comments', CommentController::class)
        ->middleware('role:manager');

    /* ---------- Users (manager only) ---------- */
    Route::resource('users', UserController::class)
        ->middleware('role:manager')
        ->except(['show']);

    /* ---------- Receipts (PDF) ---------- */
    Route::get('/receipts/{order}/customer', [ReceiptController::class, 'customer'])
        ->middleware('role:waiter|manager')
        ->name('receipts.customer');

    Route::get('/receipts/{order}/kitchen', [ReceiptController::class, 'kitchen'])
        ->middleware('role:kitchen|bar|manager')
        ->name('receipts.kitchen');

    /* ---------- Logout ---------- */
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

require __DIR__ . '/auth.php';
