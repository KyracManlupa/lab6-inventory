
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Inventory Transactions
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->has('transaction'))
                <div class="mb-4 rounded-lg bg-red-100 p-4 text-red-800">
                    {{ $errors->first('transaction') }}
                </div>
            @endif

            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">
                        Transaction History
                    </h3>
                    <p class="text-sm text-gray-500">
                        View and filter all inventory movements.
                    </p>
                </div>

                <a href="{{ route('transactions.create') }}"
                   class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    + New Transaction
                </a>
            </div>

            <div class="mb-6 rounded-lg bg-white p-5 shadow-sm">
                <form method="GET" action="{{ route('transactions.index') }}"
                      class="grid grid-cols-1 gap-4 md:grid-cols-4">

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Product
                        </label>
                        <select name="product_id" class="w-full rounded-lg border-gray-300">
                            <option value="">All Products</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    @selected(request('product_id') == $product->id)>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Transaction Type
                        </label>
                        <select name="type" class="w-full rounded-lg border-gray-300">
                            <option value="">All Types</option>
                            <option value="in" @selected(request('type') === 'in')>
                                Stock In
                            </option>
                            <option value="out" @selected(request('type') === 'out')>
                                Stock Out
                            </option>
                            <option value="adjustment" @selected(request('type') === 'adjustment')>
                                Adjustment
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Date
                        </label>
                        <input type="date" name="date" value="{{ request('date') }}"
                               class="w-full rounded-lg border-gray-300">
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                            Filter
                        </button>

                        <a href="{{ route('transactions.index') }}"
                           class="rounded-lg border px-4 py-2 text-sm font-semibold text-gray-700">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-lg bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Date</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Product</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Type</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Quantity</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Reference</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">User</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">
                                        {{ $transaction->created_at->format('M d, Y h:i A') }}
                                    </td>

                                    <td class="px-5 py-4 text-sm font-medium text-gray-900">
                                        {{ $transaction->product->name ?? 'Deleted Product' }}
                                    </td>

                                    <td class="px-5 py-4 text-sm">
                                        @php
                                            $badgeColors = [
                                                'in' => 'bg-green-100 text-green-800',
                                                'out' => 'bg-red-100 text-red-800',
                                                'adjustment' => 'bg-yellow-100 text-yellow-800',
                                            ];
                                        @endphp

                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badgeColors[$transaction->type] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($transaction->type) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-sm font-semibold text-gray-700">
                                        {{ $transaction->type === 'out' ? '-' : ($transaction->type === 'in' ? '+' : '') }}{{ abs($transaction->quantity) }}
                                    </td>

                                    <td class="px-5 py-4 text-sm text-gray-600">
                                        {{ $transaction->reference_number ?? '—' }}
                                    </td>

                                    <td class="px-5 py-4 text-sm text-gray-600">
                                        {{ $transaction->user->name ?? 'Unknown' }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('transactions.show', $transaction) }}"
                                               class="font-semibold text-indigo-600 hover:text-indigo-900">
                                                View
                                            </a>

                                            @if (auth()->user()->is_admin)
                                                <form method="POST"
                                                      action="{{ route('transactions.destroy', $transaction) }}"
                                                      onsubmit="return confirm('Are you sure you want to delete this transaction? The stock quantity will also be reversed.');">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="font-semibold text-red-600 hover:text-red-900">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-10 text-center text-sm text-gray-500">
                                        No inventory transactions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 px-5 py-4">
                    {{ $transactions->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
