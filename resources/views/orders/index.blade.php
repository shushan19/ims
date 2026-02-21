@extends('layouts.app')
@section('title', 'Orders')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">🧾 Orders</h1>
        <a href="{{ route('orders.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            + New Order
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-left">Order #</th>
                <th class="px-6 py-4 text-left">Items</th>
                <th class="px-6 py-4 text-left">Total</th>
                <th class="px-6 py-4 text-left">Status</th>
                <th class="px-6 py-4 text-left">Date</th>
                <th class="px-6 py-4 text-left">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse($orders as $order)
                @php
                    $colors = [
                        'pending'   => 'bg-gray-100 text-gray-600',
                        'preparing' => 'bg-yellow-100 text-yellow-700',
                        'delivered' => 'bg-green-100 text-green-700',
                        'cancelled' => 'bg-red-100 text-red-600',
                    ];
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-semibold text-gray-800">#{{ $order->id }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $order->orderItems->count() }} item(s)</td>
                    <td class="px-6 py-4 font-medium text-gray-800">${{ number_format($order->total_amount, 2) }}</td>
                    <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $colors[$order->status] ?? 'bg-gray-100' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('orders.show', $order) }}"
                           class="bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1.5 rounded-lg transition">
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">No orders yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
@endsection
