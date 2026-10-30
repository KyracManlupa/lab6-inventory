
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Inventory Transaction
            </h2>

            <a href="{{ route('transactions.index') }}"
               class="text-sm text-gray-600 hover:text-gray-900">
                Back to Transactions
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-6">
                    Transaction Details
                </h3>

                @if ($errors->any())
                    <div class="mb-5 rounded-md bg-red-50 p-4 text-red-700">
                        <p class="font-semibold mb-2">Please check the following:</p>
                        <ul class="list-disc pl-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('transactions.store') }}">
                    @csrf

                    <!-- Product -->
                    <div class="mb-5">
                        <label for="product_id"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Select Product
                        </label>

                        <select name="product_id" id="product_id" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Choose a product</option>

                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                        data-stock="{{ $product->quantity }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} (Stock: {{ $product->quantity }})
                                </option>
                            @endforeach
                        </select>

                        <p class="mt-2 text-sm text-gray-500">
                            Current Stock:
                            <strong id="current-stock">Select a product</strong>
                        </p>

                        @error('product_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Transaction Type -->
                    <div class="mb-5">
                        <label for="type"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Transaction Type
                        </label>

                        <select name="type" id="type" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Choose transaction type</option>
                            <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>
                                Stock In
                            </option>
                            <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>
                                Stock Out
                            </option>
                            <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>
                                Stock Adjustment
                            </option>
                        </select>

                        @error('type')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div class="mb-5">
                        <label for="quantity"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Quantity
                        </label>

                        <input type="number" name="quantity" id="quantity"
                               value="{{ old('quantity') }}"
                               required step="1"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Enter quantity">

                        <p id="quantity-help" class="mt-2 text-sm text-gray-500">
                            Select a transaction type first.
                        </p>

                        @error('quantity')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reference Number -->
                    <div class="mb-5">
                        <label for="reference_number"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Reference Number (Optional)
                        </label>

                        <input type="text" name="reference_number"
                               id="reference_number"
                               value="{{ old('reference_number') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Example: PO-2026-001">

                        @error('reference_number')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label for="notes"
                               class="block text-sm font-medium text-gray-700 mb-2">
                            Notes (Optional)
                        </label>

                        <textarea name="notes" id="notes" rows="4"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Enter transaction details or reason">{{ old('notes') }}</textarea>

                        @error('notes')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('transactions.index') }}"
                           class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>

                        <button type="submit"
                                class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                            Save Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const productSelect = document.getElementById('product_id');
        const typeSelect = document.getElementById('type');
        const quantityInput = document.getElementById('quantity');
        const currentStock = document.getElementById('current-stock');
        const quantityHelp = document.getElementById('quantity-help');

        function updateTransactionForm() {
            const selectedOption =
                productSelect.options[productSelect.selectedIndex];

            const stock = selectedOption.dataset.stock;
            const type = typeSelect.value;

            currentStock.textContent =
                stock !== undefined ? stock + ' units' : 'Select a product';

            quantityInput.removeAttribute('max');

            if (type === 'adjustment') {
                quantityInput.removeAttribute('min');
                quantityHelp.textContent =
                    'Enter the stock difference. Example: +10 adds 10 units; -5 removes 5 units.';
            } else if (type === 'in') {
                quantityInput.min = 1;
                quantityHelp.textContent =
                    'Enter a positive quantity to add stock.';
            } else if (type === 'out') {
                quantityInput.min = 1;

                if (stock !== undefined) {
                    quantityInput.max = stock;
                    quantityHelp.textContent =
                        'Enter a positive quantity to remove stock. Maximum available: ' +
                        stock + ' units.';
                } else {
                    quantityHelp.textContent =
                        'Enter a positive quantity to remove stock.';
                }
            } else {
                quantityInput.removeAttribute('min');
                quantityHelp.textContent =
                    'Select a transaction type first.';
            }
        }

        productSelect.addEventListener('change', updateTransactionForm);
        typeSelect.addEventListener('change', updateTransactionForm);

        updateTransactionForm();
    </script>
</x-app-layout>
