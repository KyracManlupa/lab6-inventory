<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Product Details</h2>
                <p class="mt-1 text-sm text-gray-500">View the complete information for this inventory item.</p>
            </div>

            <a href="{{ route('products.index') }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Back to Inventory
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="bg-indigo-700 px-6 py-6 text-white sm:px-8">
                    <p class="text-sm font-medium text-indigo-100">Product Name</p>
                    <h3 class="mt-1 text-2xl font-bold">{{ $product->name }}</h3>
                    <p class="mt-2 text-sm text-indigo-100">SKU: {{ $product->sku }}</p>
                </div>

                <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2 sm:p-8">
                    <div class="rounded-lg bg-gray-50 p-5">
                        <p class="text-sm font-medium text-gray-500">Price</p>
                        <p class="mt-2 text-2xl font-bold text-indigo-700">
                            ₱{{ number_format((float) $product->price, 2) }}
                        </p>
                    </div>

                    <div class="rounded-lg bg-gray-50 p-5">
                        <p class="text-sm font-medium text-gray-500">Available Quantity</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            {{ $product->quantity }} units
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">Category</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $product->category }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">Supplier</p>
                        <p class="mt-1 font-semibold text-gray-900">
                            {{ $product->supplier ?: 'Not specified' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">Reorder Level</p>
                        <p class="mt-1 font-semibold text-gray-900">
                            {{ $product->reorder_level }} units
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">Stock Status</p>
                        <div class="mt-2">
                            @if ($product->quantity == 0)
                                <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-700">
                                    Out of Stock
                                </span>
                            @elseif ($product->quantity <= $product->reorder_level)
                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-700">
                                    Low Stock
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700">
                                    In Stock
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-sm font-medium text-gray-500">Description</p>
                        <p class="mt-2 whitespace-pre-line text-gray-800">
                            {{ $product->description ?: 'No description provided.' }}
                        </p>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-sm font-medium text-gray-500">Date Created</p>
                        <p class="mt-1 text-sm text-gray-800">
                            {{ $product->created_at?->format('F d, Y h:i A') ?? 'Not available' }}
                        </p>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-sm font-medium text-gray-500">Last Updated</p>
                        <p class="mt-1 text-sm text-gray-800">
                            {{ $product->updated_at?->format('F d, Y h:i A') ?? 'Not available' }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 sm:flex-row sm:justify-end">
                    <a href="{{ route('products.edit', $product) }}"
                       class="inline-flex justify-center rounded-lg bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-amber-600">
                        Edit Product
                    </a>

                    <a href="{{ route('products.index') }}"
                       class="inline-flex justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                        Return to Inventory
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>