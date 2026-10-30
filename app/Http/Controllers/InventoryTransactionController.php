
<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class InventoryTransactionController extends Controller
{
    // Display transaction records
    public function index(Request $request)
    {
        $transactions = InventoryTransaction::with(['product', 'user'])
            ->when($request->filled('product_id'), function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->filled('date'), function ($query) use ($request) {
                $query->whereDate('created_at', $request->date);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('transactions.index', [
            'transactions' => $transactions,
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    // Show transaction creation form
    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('transactions.create', compact('products'));
    }

    // Save a new inventory transaction
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', Rule::in(['in', 'out', 'adjustment'])],
            'quantity' => ['required', 'integer', 'not_in:0'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        if (
            in_array($validated['type'], ['in', 'out']) &&
            $validated['quantity'] < 1
        ) {
            throw ValidationException::withMessages([
                'quantity' => 'Stock In and Stock Out quantities must be positive.',
            ]);
        }

        DB::transaction(function () use ($validated, $request) {
            InventoryTransaction::create([
                ...$validated,
                'user_id' => $request->user()->id,
            ]);
        });

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Inventory transaction created successfully.');
    }

    // Display one transaction
    public function show(InventoryTransaction $transaction)
    {
        $transaction->load(['product', 'user']);

        return view('transactions.show', compact('transaction'));
    }

    // Delete transaction and reverse its stock movement
    public function destroy(InventoryTransaction $transaction)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Only administrators can delete transactions.');
        }

        DB::transaction(function () use ($transaction) {

            // Lock the transaction to prevent simultaneous deletion.
            $lockedTransaction = InventoryTransaction::whereKey($transaction->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Lock the related product before changing its stock.
            $product = Product::whereKey($lockedTransaction->product_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Reverse the original stock movement.
            if ($lockedTransaction->type === 'in') {
                $newQuantity = $product->quantity - $lockedTransaction->quantity;

            } elseif ($lockedTransaction->type === 'out') {
                $newQuantity = $product->quantity + $lockedTransaction->quantity;

            } elseif ($lockedTransaction->type === 'adjustment') {
                $newQuantity = $product->quantity - $lockedTransaction->quantity;

            } else {
                throw ValidationException::withMessages([
                    'transaction' => 'Invalid transaction type.',
                ]);
            }

            // Prevent stock from becoming negative.
            if ($newQuantity < 0) {
                throw ValidationException::withMessages([
                    'transaction' => 'This transaction cannot be deleted because it would result in negative stock.',
                ]);
            }

            // Save the reversed stock quantity.
            $product->update([
                'quantity' => $newQuantity,
            ]);

            // Delete the transaction record.
            $lockedTransaction->delete();
        });

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaction deleted and stock reversed successfully.');
    }
}
