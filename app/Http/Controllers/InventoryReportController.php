<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Inventory Summary
        $totalProducts = Product::count();
        $totalStock = (int) Product::sum('quantity');

        $inventoryValue = (float) Product::selectRaw(
            'COALESCE(SUM(price * quantity), 0) as total'
        )->value('total');

        $lowStockCount = Product::whereColumn(
            'quantity',
            '<=',
            'reorder_level'
        )->count();

        $outOfStockCount = Product::where('quantity', '<=', 0)->count();

        // Stock Movement Report
        $transactions = InventoryTransaction::with(['product', 'user'])
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stockIn = (int) InventoryTransaction::where('type', 'in')
            ->when($startDate, fn ($query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('created_at', '<=', $endDate))
            ->sum('quantity');

        $stockOut = (int) InventoryTransaction::where('type', 'out')
            ->when($startDate, fn ($query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('created_at', '<=', $endDate))
            ->sum('quantity');

        $adjustments = InventoryTransaction::where('type', 'adjustment')
            ->when($startDate, fn ($query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn ($query) => $query->whereDate('created_at', '<=', $endDate))
            ->sum('quantity');

        // Low Stock Report
        $lowStockProducts = Product::whereColumn(
            'quantity',
            '<=',
            'reorder_level'
        )
            ->orderBy('quantity')
            ->get();

        // Inventory Valuation Report
        $products = Product::orderBy('name')->get();

        return view('reports.index', compact(
            'startDate',
            'endDate',
            'totalProducts',
            'totalStock',
            'inventoryValue',
            'lowStockCount',
            'outOfStockCount',
            'transactions',
            'stockIn',
            'stockOut',
            'adjustments',
            'lowStockProducts',
            'products'
        ));
    }
}
