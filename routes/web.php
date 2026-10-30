
<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InventoryReportController;
use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $totalProducts = Product::count();
    $totalStock = (int) Product::sum('quantity');

    $lowStockProducts = Product::whereColumn(
        'quantity',
        '<=',
        'reorder_level'
    )->count();

    $lowStockProductList = Product::whereColumn(
        'quantity',
        '<=',
        'reorder_level'
    )
        ->orderBy('quantity')
        ->limit(5)
        ->get();

    $recentTransactions = InventoryTransaction::with(['product', 'user'])
        ->latest()
        ->limit(5)
        ->get();

    $inventoryValue = (float) Product::selectRaw(
        'COALESCE(SUM(price * quantity), 0) as total'
    )->value('total');

    return view('dashboard', compact(
        'totalProducts',
        'totalStock',
        'lowStockProducts',
        'lowStockProductList',
        'recentTransactions',
        'inventoryValue'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated Routes
Route::middleware('auth')->group(function () {

    // Dashboard AJAX Statistics
    Route::get('/dashboard/stats', function () {
        $totalProducts = Product::count();
        $totalStock = (int) Product::sum('quantity');

        $lowStockProducts = Product::whereColumn(
            'quantity',
            '<=',
            'reorder_level'
        )->count();

        $lowStockProductList = Product::whereColumn(
            'quantity',
            '<=',
            'reorder_level'
        )
            ->orderBy('quantity')
            ->limit(5)
            ->get(['id', 'name', 'sku', 'quantity', 'reorder_level']);

        $recentTransactions = InventoryTransaction::with(['product', 'user'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'product_name' => $transaction->product?->name ?? 'Deleted Product',
                    'user_name' => $transaction->user?->name ?? 'Unknown User',
                    'type' => $transaction->type,
                    'quantity' => $transaction->quantity,
                    'created_at' => $transaction->created_at?->format('M d, Y h:i A'),
                ];
            });

        $inventoryValue = (float) Product::selectRaw(
            'COALESCE(SUM(price * quantity), 0) as total'
        )->value('total');

        return response()->json([
            'totalProducts' => $totalProducts,
            'totalStock' => $totalStock,
            'lowStockProducts' => $lowStockProducts,
            'lowStockProductList' => $lowStockProductList,
            'recentTransactions' => $recentTransactions,
            'inventoryValue' => $inventoryValue,
        ]);
    })->name('dashboard.stats');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Product Routes
    Route::resource('products', ProductController::class);

    // Inventory Transaction Routes
    Route::resource('transactions', InventoryTransactionController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);

    // Inventory Reports
    Route::get('/reports', [InventoryReportController::class, 'index'])
        ->name('reports.index');

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.readAll');
});

require __DIR__.'/auth.php';
