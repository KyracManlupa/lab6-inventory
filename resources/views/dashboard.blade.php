
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    Inventory Dashboard
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    Overview of your hardware inventory and stock levels.
                </p>
            </div>
            <span class="rounded-full bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700">
                Inventory System
            </span>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-100 py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            <!-- Welcome Banner -->
            <section class="overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-indigo-800 p-6 text-white shadow-lg sm:p-8">
                <p class="text-sm font-bold uppercase tracking-widest text-indigo-200">
                    Inventory Management System
                </p>
                <h3 class="mt-3 text-2xl font-extrabold sm:text-3xl">
                    Welcome to Your Dashboard!
                </h3>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-200">
                    Manage your hardware products, monitor stock availability, and keep track of your inventory value in one place.
                </p>
                <a href="{{ route('products.index') }}"
                   class="mt-6 inline-flex items-center rounded-lg bg-white px-5 py-3 text-sm font-bold text-indigo-800 shadow transition hover:bg-indigo-50">
                    Manage Products <span class="ml-2">→</span>
                </a>
            </section>

            <!-- Live Update Status -->
            <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 shadow-sm">
                <div class="flex items-center gap-2">
                    <span id="dashboard-status-dot" class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                    <span id="dashboard-status" class="text-sm font-semibold text-slate-700">
                        Live dashboard updates enabled
                    </span>
                </div>
                <p class="text-xs text-slate-500">
                    Last updated:
                    <span id="dashboard-last-updated">Loading...</span>
                </p>
            </div>

            <!-- Statistics -->
            <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-sm font-semibold text-slate-600">Total Products</p>
                        <span class="rounded-xl bg-indigo-100 p-3 text-xl">📦</span>
                    </div>
                    <p id="total-products" class="mt-4 text-3xl font-extrabold text-slate-900">
                        {{ number_format($totalProducts) }}
                    </p>
                    <p class="mt-2 text-sm text-slate-500">Registered inventory items</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-sm font-semibold text-slate-600">Total Stock</p>
                        <span class="rounded-xl bg-blue-100 p-3 text-xl">🗃️</span>
                    </div>
                    <p id="total-stock" class="mt-4 text-3xl font-extrabold text-slate-900">
                        {{ number_format($totalStock) }}
                    </p>
                    <p class="mt-2 text-sm text-slate-500">Units currently available</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-sm font-semibold text-slate-600">Low-Stock Products</p>
                        <span class="rounded-xl bg-amber-100 p-3 text-xl">⚠️</span>
                    </div>
                    <p id="low-stock-products" class="mt-4 text-3xl font-extrabold text-amber-700">
                        {{ number_format($lowStockProducts) }}
                    </p>
                    <p class="mt-2 text-sm text-slate-500">At or below reorder level</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-sm font-semibold text-slate-600">Inventory Value</p>
                        <span class="rounded-xl bg-emerald-100 p-3 text-xl">💰</span>
                    </div>
                    <p id="inventory-value" class="mt-4 break-words text-2xl font-extrabold text-emerald-700">
                        ₱{{ number_format($inventoryValue, 2) }}
                    </p>
                    <p class="mt-2 text-sm text-slate-500">Price multiplied by stock quantity</p>
                </div>

            </section>

            <!-- Low-Stock Products -->
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-6 py-5">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Low-Stock Products</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Products that have reached or fallen below their reorder level.
                        </p>
                    </div>
                    <a href="{{ route('products.index') }}"
                       class="rounded-lg bg-indigo-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-800">
                        View All Products
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">SKU</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Current Stock</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Reorder Level</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                            </tr>
                        </thead>
                        <tbody id="low-stock-table-body" class="divide-y divide-slate-100 bg-white">
                            @forelse ($lowStockProductList as $product)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-900">
                                        {{ $product->name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $product->sku }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center text-sm font-bold text-amber-700">
                                        {{ $product->quantity }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-slate-700">
                                        {{ $product->reorder_level }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        @if ($product->quantity <= 0)
                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">Out of Stock</span>
                                        @else
                                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">Low Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                                        No low-stock products found. Your stock levels look good!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 px-6 py-3 text-xs text-slate-500">
                    Showing up to 5 products that need attention.
                </div>
            </section>

            <!-- Recent Inventory Transactions -->
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-6 py-5">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Recent Inventory Transactions</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Latest stock movements recorded in the inventory system.
                        </p>
                    </div>
                    <a href="{{ route('transactions.index') }}"
                       class="rounded-lg bg-indigo-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-800">
                        View All Transactions
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Product</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Type</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Recorded By</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Date</th>
                            </tr>
                        </thead>
                        <tbody id="recent-transactions-table-body" class="divide-y divide-slate-100 bg-white">
                            @forelse ($recentTransactions as $transaction)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-900">
                                        {{ $transaction->product?->name ?? 'Deleted Product' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold
                                            {{ $transaction->type === 'in' ? 'bg-emerald-100 text-emerald-700' : ($transaction->type === 'out' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                            {{ $transaction->type === 'in' ? 'Stock In' : ($transaction->type === 'out' ? 'Stock Out' : 'Adjustment') }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center text-sm font-bold text-slate-800">
                                        {{ $transaction->quantity }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $transaction->user?->name ?? 'Unknown User' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $transaction->created_at?->format('M d, Y h:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                                        No inventory transactions recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 px-6 py-3 text-xs text-slate-500">
                    Showing the 5 most recent inventory transactions.
                </div>
            </section>

            <!-- Management and Summary -->
            <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                    <h3 class="text-lg font-bold text-slate-900">Inventory Management</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Keep your hardware inventory organized by adding products, updating stock quantities, checking product details, and removing discontinued items.
                    </p>

                    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <p class="font-bold text-slate-800">Add Products</p>
                            <p class="mt-2 text-sm leading-5 text-slate-600">Register new hardware items.</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <p class="font-bold text-slate-800">Track Stock</p>
                            <p class="mt-2 text-sm leading-5 text-slate-600">Monitor quantity and stock status.</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <p class="font-bold text-slate-800">Update Records</p>
                            <p class="mt-2 text-sm leading-5 text-slate-600">Maintain accurate product details.</p>
                        </div>
                    </div>

                    <a href="{{ route('products.index') }}"
                       class="mt-6 inline-flex items-center rounded-lg bg-indigo-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-indigo-800">
                        Go to Product Inventory <span class="ml-2">→</span>
                    </a>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900">Quick Summary</h3>

                    <div class="mt-5 space-y-4">
                        <div class="flex items-center justify-between gap-3 border-b border-slate-200 pb-3">
                            <span class="text-sm text-slate-600">Product Records</span>
                            <span id="summary-products" class="font-bold text-slate-900">{{ number_format($totalProducts) }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-3 border-b border-slate-200 pb-3">
                            <span class="text-sm text-slate-600">Units in Stock</span>
                            <span id="summary-stock" class="font-bold text-slate-900">{{ number_format($totalStock) }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm text-slate-600">Items Needing Attention</span>
                            <span id="summary-low-stock" class="font-bold {{ $lowStockProducts > 0 ? 'text-amber-700' : 'text-emerald-700' }}">
                                {{ number_format($lowStockProducts) }}
                            </span>
                        </div>
                    </div>

                    <div id="stock-status-box" class="mt-6 rounded-xl border p-4 {{ $lowStockProducts > 0 ? 'border-amber-200 bg-amber-50 text-amber-950' : 'border-emerald-200 bg-emerald-50 text-emerald-950' }}">
                        <p id="stock-status-title" class="text-sm font-bold">
                            {{ $lowStockProducts > 0 ? 'Stock Review Recommended' : 'Stock Levels Look Good' }}
                        </p>
                        <p id="stock-status-message" class="mt-2 text-sm leading-5">
                            {{ $lowStockProducts > 0 ? 'Review low-stock and out-of-stock products regularly.' : 'No products are currently at or below their reorder level.' }}
                        </p>
                    </div>
                </div>

            </section>

            <footer class="pb-2 pt-2 text-center text-xs font-medium text-slate-500">
                Inventory Management System · Laravel
            </footer>

        </div>
    </div>

    <!-- Dashboard AJAX Auto Refresh -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statsUrl = @json(route('dashboard.stats'));

            const elements = {
                totalProducts: document.getElementById('total-products'),
                totalStock: document.getElementById('total-stock'),
                lowStock: document.getElementById('low-stock-products'),
                inventoryValue: document.getElementById('inventory-value'),
                summaryProducts: document.getElementById('summary-products'),
                summaryStock: document.getElementById('summary-stock'),
                summaryLowStock: document.getElementById('summary-low-stock'),
                status: document.getElementById('dashboard-status'),
                statusDot: document.getElementById('dashboard-status-dot'),
                lastUpdated: document.getElementById('dashboard-last-updated'),
                stockBox: document.getElementById('stock-status-box'),
                stockTitle: document.getElementById('stock-status-title'),
                stockMessage: document.getElementById('stock-status-message'),
                lowStockTable: document.getElementById('low-stock-table-body'),
                recentTransactionsTable: document.getElementById('recent-transactions-table-body')
            };

            const numberFormat = new Intl.NumberFormat('en-PH');

            function escapeHtml(value) {
                return String(value ?? '').replace(/[&<>"']/g, function (character) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    }[character];
                });
            }

            function renderLowStockProducts(products) {
                if (!products || products.length === 0) {
                    elements.lowStockTable.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                                No low-stock products found. Your stock levels look good!
                            </td>
                        </tr>`;
                    return;
                }

                elements.lowStockTable.innerHTML = products.map(product => {
                    const outOfStock = Number(product.quantity) <= 0;
                    const status = outOfStock
                        ? '<span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">Out of Stock</span>'
                        : '<span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">Low Stock</span>';

                    return `
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-900">${escapeHtml(product.name)}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">${escapeHtml(product.sku)}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-center text-sm font-bold text-amber-700">${numberFormat.format(product.quantity)}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-slate-700">${numberFormat.format(product.reorder_level)}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">${status}</td>
                        </tr>`;
                }).join('');
            }

            function renderRecentTransactions(transactions) {
                if (!transactions || transactions.length === 0) {
                    elements.recentTransactionsTable.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                                No inventory transactions recorded yet.
                            </td>
                        </tr>`;
                    return;
                }

                elements.recentTransactionsTable.innerHTML = transactions.map(transaction => {
                    const type = String(transaction.type || '').toLowerCase();

                    const badgeClass = type === 'in'
                        ? 'bg-emerald-100 text-emerald-700'
                        : type === 'out'
                            ? 'bg-red-100 text-red-700'
                            : 'bg-blue-100 text-blue-700';

                    const typeLabel = type === 'in'
                        ? 'Stock In'
                        : type === 'out'
                            ? 'Stock Out'
                            : 'Adjustment';

                    return `
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-900">${escapeHtml(transaction.product_name)}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                <span class="rounded-full px-3 py-1 text-xs font-bold ${badgeClass}">${typeLabel}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-center text-sm font-bold text-slate-800">${numberFormat.format(transaction.quantity)}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">${escapeHtml(transaction.user_name)}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">${escapeHtml(transaction.created_at ?? '')}</td>
                        </tr>`;
                }).join('');
            }

            async function refreshDashboard() {
                try {
                    const response = await fetch(statsUrl, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        cache: 'no-store'
                    });

                    if (!response.ok) {
                        throw new Error('Unable to retrieve dashboard statistics.');
                    }

                    const data = await response.json();

                    elements.totalProducts.textContent = numberFormat.format(data.totalProducts);
                    elements.totalStock.textContent = numberFormat.format(data.totalStock);
                    elements.lowStock.textContent = numberFormat.format(data.lowStockProducts);
                    elements.inventoryValue.textContent = '₱' + numberFormat.format(data.inventoryValue);

                    elements.summaryProducts.textContent = numberFormat.format(data.totalProducts);
                    elements.summaryStock.textContent = numberFormat.format(data.totalStock);
                    elements.summaryLowStock.textContent = numberFormat.format(data.lowStockProducts);

                    renderLowStockProducts(data.lowStockProductList);
                    renderRecentTransactions(data.recentTransactions);

                    if (data.lowStockProducts > 0) {
                        elements.summaryLowStock.className = 'font-bold text-amber-700';
                        elements.stockBox.className = 'mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-950';
                        elements.stockTitle.textContent = 'Stock Review Recommended';
                        elements.stockMessage.textContent = 'Review low-stock and out-of-stock products regularly.';
                    } else {
                        elements.summaryLowStock.className = 'font-bold text-emerald-700';
                        elements.stockBox.className = 'mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-950';
                        elements.stockTitle.textContent = 'Stock Levels Look Good';
                        elements.stockMessage.textContent = 'No products are currently at or below their reorder level.';
                    }

                    elements.status.textContent = 'Live dashboard updates enabled';
                    elements.statusDot.className = 'h-2.5 w-2.5 rounded-full bg-green-500';
                    elements.lastUpdated.textContent = new Date().toLocaleTimeString();

                } catch (error) {
                    console.error('Dashboard update error:', error);
                    elements.status.textContent = 'Update unavailable. Retrying automatically.';
                    elements.statusDot.className = 'h-2.5 w-2.5 rounded-full bg-red-500';
                }
            }

            refreshDashboard();
            setInterval(refreshDashboard, 30000);
        });
    </script>
</x-app-layout>
