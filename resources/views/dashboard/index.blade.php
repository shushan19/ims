@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

    <h1 class="text-2xl font-bold text-gray-800 mb-6">📊 Dashboard</h1>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-4 gap-4 mb-8">

        <div class="bg-blue-500 text-white rounded-xl p-5 shadow">
            <div class="text-3xl font-bold">{{ $totalOrders }}</div>
            <div class="text-blue-100 text-sm mt-1">Total Orders</div>
        </div>

        <div class="bg-green-500 text-white rounded-xl p-5 shadow">
            <div class="text-3xl font-bold">{{ $deliveredToday }}</div>
            <div class="text-green-100 text-sm mt-1">Delivered Today</div>
        </div>

        <div class="bg-indigo-500 text-white rounded-xl p-5 shadow">
            <div class="text-3xl font-bold">${{ number_format($totalRevenue, 2) }}</div>
            <div class="text-indigo-100 text-sm mt-1">Total Revenue</div>
        </div>

        <div class="{{ $lowStockCount > 0 ? 'bg-red-500' : 'bg-gray-400' }} text-white rounded-xl p-5 shadow">
            <div class="text-3xl font-bold">{{ $lowStockCount }}</div>
            <div class="text-sm mt-1 opacity-80">Low Stock Alerts</div>
        </div>

    </div>

    <div class="grid grid-cols-2 gap-6">

        {{-- Low Stock Warning --}}
        @if($lowStockItems->isNotEmpty())
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="bg-red-500 text-white px-5 py-3 font-semibold text-sm">
                    ⚠️ Low Stock Ingredients
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">Ingredient</th>
                        <th class="px-5 py-3 text-left">Current</th>
                        <th class="px-5 py-3 text-left">Minimum</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @foreach($lowStockItems as $item)
                        <tr class="bg-red-50">
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $item->name }}</td>
                            <td class="px-5 py-3 text-red-600 font-semibold">{{ $item->current_stock }} {{ $item->unit }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $item->minimum_stock }} {{ $item->unit }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Recent Orders --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="bg-gray-800 text-white px-5 py-3 font-semibold text-sm">
                🕐 Recent Orders
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3 text-left">Order</th>
                    <th class="px-5 py-3 text-left">Items</th>
                    <th class="px-5 py-3 text-left">Total</th>
                    <th class="px-5 py-3 text-left">Status</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @foreach($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:underline font-medium">
                                #{{ $order->id }}
                            </a>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $order->orderItems->count() }}</td>
                        <td class="px-5 py-3 text-gray-800 font-medium">${{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-5 py-3">
                            @php
                                $colors = [
                                    'pending'   => 'bg-gray-100 text-gray-600',
                                    'preparing' => 'bg-yellow-100 text-yellow-700',
                                    'delivered' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-600',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $colors[$order->status] ?? 'bg-gray-100' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
