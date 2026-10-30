<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    Product Inventory
                </h2>
                <p class="text-sm text-gray-500">
                    Manage your hardware products and stock levels.
                </p>
            </div>

            <a href="{{ route('products.create') }}"
               class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700">
                + Add Product
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                    <p class="font-semibold">Please check the following:</p>
                    <ul class="mt-2 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Total Products</p>
                    <p class="mt-2 text-3xl font-bold text-indigo-700">
                        {{ $products->total() }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Current Page Items</p>
                    <p class="mt-2 text-3xl font-bold text-gray-800">
                        {{ $products->count() }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Inventory Status</p>
                    <p class="mt-2 text-lg font-bold text-green-700">Stock Tracking Active</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-800">Hardware Products</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Review and manage your registered inventory.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Category</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Price</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Quantity</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($products as $product)
                                <tr class="transition hover:bg-indigo-50/40">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $product->name }}</div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            {{ $product->supplier ?: 'No supplier specified' }}
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $product->sku }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $product->category }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-semibold text-gray-800">
                                        ₱{{ number_format((float) $product->price, 2) }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-center text-sm font-semibold text-gray-800">
                                        {{ $product->quantity }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        @if ($product->quantity == 0)
                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Out of Stock</span>
                                        @elseif ($product->quantity <= $product->reorder_level)
                                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Low Stock</span>
                                        @else
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">In Stock</span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-center text-sm">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('products.show', $product) }}"
                                               class="font-semibold text-indigo-600 hover:text-indigo-900">View</a>

                                            <a href="{{ route('products.edit', $product) }}"
                                               class="font-semibold text-amber-600 hover:text-amber-800">Edit</a>

                                            <form action="{{ route('products.destroy', $product) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="font-semibold text-red-600 hover:text-red-800">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <p class="font-semibold text-gray-700">No products found.</p>
                                        <p class="mt-1 text-sm text-gray-500">Add your first hardware product to get started.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $products->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>