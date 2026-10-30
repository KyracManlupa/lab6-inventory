
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Inventory Reports
            </h2>

            <a href="{{ route('dashboard') }}"
               class="text-sm text-gray-600 hover:text-gray-900">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Inventory Summary -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Inventory Summary
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="bg-white p-5 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500">Total Products</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($totalProducts) }}</p>
                    </div>

                    <div class="bg-white p-5 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500">Total Stock</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($totalStock) }}</p>
                    </div>

                    <div class="bg-white p-5 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500">Inventory Value</p>
                        <p class="text-2xl font-bold text-gray-800">₱{{ number_format($inventoryValue, 2) }}</p>
                    </div>

                    <div class="bg-white p-5 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500">Low Stock Products</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ number_format($lowStockCount) }}</p>
                    </div>

                    <div class="bg-white p-5 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500">Out of Stock</p>
                        <p class="text-2xl font-bold text-red-600">{{ number_format($outOfStockCount) }}</p>
                    </div>
                </div>
            </div>

            <!-- Stock Movement Report -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Stock Movement Report
                </h3>

                <form method="GET" action="{{ route('reports.index') }}"
                      class="flex flex-col sm:flex-row gap-4 mb-6">
                    <div>
                        <label for="start_date" class="block text-sm text-gray-600 mb-1">
                            Start Date
                        </label>
                        <input type="date" name="start_date" id="start_date"
                               value="{{ $startDate }}"
                               class="rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm text-gray-600 mb-1">
                            End Date
                        </label>
                        <input type="date" name="end_date" id="end_date"
                               value="{{ $endDate }}"
                               class="rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Filter
                        </button>

                        <a href="{{ route('reports.index') }}"
                           class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Reset
                        </a>
                    </div>
                </form>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div class="rounded-md bg-green-50 p-4">
                        <p class="text-sm text-green-700">Total Stock In</p>
                        <p class="text-xl font-bold text-green-800">{{ number_format($stockIn) }}</p>
                    </div>

                    <div class="rounded-md bg-red-50 p-4">
                        <p class="text-sm text-red-700">Total Stock Out</p>
                        <p class="text-xl font-bold text-red-800">{{ number_format($stockOut) }}</p>
                    </div>

                    <div class="rounded-md bg-blue-50 p-4">
                        <p class="text-sm text-blue-700">Net Adjustments</p>
                        <p class="text-xl font-bold text-blue-800">{{ number_format($adjustments) }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recorded By</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ $transaction->created_at->format('M d, Y h:i A') }}
                                    </td>

                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                        {{ $transaction->product?->name ?? 'Deleted Product' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        @if ($transaction->type === 'in')
                                            <span class="text-green-700 font-semibold">Stock In</span>
                                        @elseif ($transaction->type === 'out')
                                            <span class="text-red-700 font-semibold">Stock Out</span>
                                        @else
                                            <span class="text-blue-700 font-semibold">Adjustment</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $transaction->quantity > 0 ? '+' : '' }}{{ $transaction->quantity }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ $transaction->user?->name ?? 'Unknown User' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        No inventory transactions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-5">
                    {{ $transactions->links() }}
                </div>
            </div>

            <!-- Low Stock Report -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Low Stock Report
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Stock</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reorder Level</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($lowStockProducts as $product)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                        {{ $product->name }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ $product->sku }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $product->quantity }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $product->reorder_level }}
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        @if ($product->quantity <= 0)
                                            <span class="font-semibold text-red-600">Out of Stock</span>
                                        @else
                                            <span class="font-semibold text-yellow-600">Low Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        No low-stock products found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Inventory Valuation Report -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Inventory Valuation Report
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Value</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($products as $product)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                        {{ $product->name }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        ₱{{ number_format($product->price, 2) }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ number_format($product->quantity) }}
                                    </td>

                                    <td class="px-4 py-3 text-sm font-semibold text-gray-800">
                                        ₱{{ number_format($product->price * $product->quantity, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                        No products found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700">
                                    Total Inventory Value
                                </td>
                                <td class="px-4 py-3 font-bold text-gray-900">
                                    ₱{{ number_format($inventoryValue, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
