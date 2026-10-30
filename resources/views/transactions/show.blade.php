
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Transaction Details
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

                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Transaction #{{ $transaction->id }}
                    </h3>

                    @if ($transaction->type === 'in')
                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            Stock In
                        </span>
                    @elseif ($transaction->type === 'out')
                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                            Stock Out
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                            Adjustment
                        </span>
                    @endif
                </div>

                <div class="space-y-5">

                    <div class="border-b pb-4">
                        <p class="text-sm text-gray-500">Product</p>
                        <p class="text-base font-medium text-gray-900">
                            {{ $transaction->product->name ?? 'Product unavailable' }}
                        </p>
                    </div>

                    <div class="border-b pb-4">
                        <p class="text-sm text-gray-500">Quantity</p>
                        <p class="text-base font-semibold text-gray-900">
                            @if ($transaction->type === 'in')
                                +{{ $transaction->quantity }}
                            @elseif ($transaction->type === 'out')
                                -{{ $transaction->quantity }}
                            @else
                                {{ $transaction->quantity > 0 ? '+' : '' }}{{ $transaction->quantity }}
                            @endif
                        </p>
                    </div>

                    <div class="border-b pb-4">
                        <p class="text-sm text-gray-500">Reference Number</p>
                        <p class="text-base text-gray-900">
                            {{ $transaction->reference_number ?: 'Not provided' }}
                        </p>
                    </div>

                    <div class="border-b pb-4">
                        <p class="text-sm text-gray-500">Recorded By</p>
                        <p class="text-base text-gray-900">
                            {{ $transaction->user->name ?? 'Unknown user' }}
                        </p>
                    </div>

                    <div class="border-b pb-4">
                        <p class="text-sm text-gray-500">Date and Time</p>
                        <p class="text-base text-gray-900">
                            {{ $transaction->created_at->format('F d, Y - h:i A') }}
                        </p>
                    </div>

                    <div class="pb-4">
                        <p class="text-sm text-gray-500">Notes</p>
                        <p class="text-base text-gray-900 whitespace-pre-line">
                            {{ $transaction->notes ?: 'No notes provided.' }}
                        </p>
                    </div>

                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('transactions.index') }}"
                       class="px-4 py-2 rounded-md bg-gray-800 text-white hover:bg-gray-700">
                        Back to List
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
