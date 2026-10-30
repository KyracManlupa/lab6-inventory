
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Notifications
            </h2>

            @if(auth()->user()->unreadNotifications->count() > 0)
                <form method="POST" action="{{ route('notifications.readAll') }}">
                    @csrf
                    <button type="submit"
                        class="text-sm text-blue-600 hover:text-blue-800">
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @forelse($notifications as $notification)
                    <div class="border-b border-gray-200 py-4
                        {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">

                        <div class="flex items-start justify-between gap-4">

                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ $notification->data['product_name'] ?? 'Product Alert' }}
                                </p>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ $notification->data['message'] ?? 'A product notification was received.' }}
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    Current Stock:
                                    {{ $notification->data['current_quantity'] ?? 'N/A' }}
                                    |
                                    Reorder Level:
                                    {{ $notification->data['reorder_level'] ?? 'N/A' }}
                                </p>

                                <p class="mt-1 text-xs text-gray-400">
                                    {{ $notification->created_at->format('M d, Y h:i A') }}
                                </p>

                                @if(isset($notification->data['product_id']))
                                    <a href="{{ route('products.show', $notification->data['product_id']) }}"
                                       class="mt-2 inline-block text-sm text-blue-600 hover:underline">
                                        View Product
                                    </a>
                                @endif
                            </div>

                            <div class="shrink-0">
                                @if(!$notification->read_at)
                                    <span class="rounded-full bg-blue-100 px-3 py-1 text-xs text-blue-700">
                                        Unread
                                    </span>

                                    <form method="POST"
                                          action="{{ route('notifications.read', $notification->id) }}"
                                          class="mt-2">
                                        @csrf
                                        <button type="submit"
                                            class="text-xs text-blue-600 hover:underline">
                                            Mark as Read
                                        </button>
                                    </form>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-600">
                                        Read
                                    </span>
                                @endif
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center">
                        <p class="text-gray-500">No notifications yet.</p>
                    </div>
                @endforelse

                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
