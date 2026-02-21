@extends('layouts.app')
@section('title', 'Order #' . $order->id)

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">🧾 Order #{{ $order->id }}</h1>
        <a href="{{ route('orders.index') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition">
            ← Back
        </a>
    </div>

    @php
        $colors = [
            'pending'   => 'bg-gray-100 text-gray-600',
            'preparing' => 'bg-yellow-100 text-yellow-700',
            'delivered' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-red-100 text-red-600',
        ];
    @endphp

    <div class="grid grid-cols-3 gap-6">

        {{-- Left: order items + stock log --}}
        <div class="col-span-2 space-y-6">

            {{-- Order Items Table --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
                    <span class="font-semibold text-gray-800 text-sm">Order Items</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $colors[$order->status] ?? '' }}">
                    {{ ucfirst($order->status) }}
                </span>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left">Item</th>
                        <th class="px-5 py-3 text-left">Qty</th>
                        <th class="px-5 py-3 text-left">Unit Price</th>
                        <th class="px-5 py-3 text-left">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $item->menuItem->name }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $item->quantity }}</td>
                            <td class="px-5 py-3 text-gray-600">${{ number_format($item->price, 2) }}</td>
                            <td class="px-5 py-3 font-semibold text-gray-800">${{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-50">
                        <td colspan="3" class="px-5 py-3 text-right font-semibold text-gray-700">Total</td>
                        <td class="px-5 py-3 font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            {{-- Stock Deduction Log (only shown after delivery) --}}
            @if($order->stockMovements->isNotEmpty())
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="bg-green-600 text-white px-5 py-3 text-sm font-semibold">
                        ✅ Stock Deducted on Delivery
                    </div>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3 text-left">Ingredient</th>
                            <th class="px-5 py-3 text-left">Amount Deducted</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @foreach($order->stockMovements as $mv)
                            <tr>
                                <td class="px-5 py-3 text-gray-700">{{ $mv->ingredient->name }}</td>
                                <td class="px-5 py-3 text-red-600 font-medium">
                                    {{ abs($mv->quantity) }} {{ $mv->ingredient->unit }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Update Status</h2>

                @if(!$order->hasBeenDelivered() && $order->status !== 'cancelled')
                    <form action="{{ route('orders.update-status', $order) }}" method="POST" class="space-y-4">
                        @csrf @method('PATCH')

                        <select name="status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach(['pending', 'preparing', 'delivered', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>

                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                            Update Status
                        </button>
                    </form>

                    <div class="mt-4 p-3 bg-blue-50 rounded-lg text-xs text-blue-700">
                        ℹ️ Stock is automatically deducted when status is set to <strong>Delivered</strong>.
                    </div>

                @else
                    <p class="text-sm text-gray-500">
                        This order is <strong>{{ $order->status }}</strong>.
                        @if($order->delivered_at)
                            <br><span class="text-xs text-gray-400">Delivered: {{ $order->delivered_at->format('d M Y, H:i') }}</span>
                        @endif
                    </p>
                @endif
            </div>

            @if($order->notes)
                <div class="bg-white rounded-xl shadow p-5">
                    <h2 class="text-sm font-semibold text-gray-700 mb-2">Notes</h2>
                    <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

    </div>
@endsection
