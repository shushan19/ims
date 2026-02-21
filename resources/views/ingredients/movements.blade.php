@extends('layouts.app')
@section('title', 'Stock Movements')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800"> Stock History: {{ $ingredient->name }}</h1>
        <a href="{{ route('ingredients.index') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition">
            ← Back
        </a>
    </div>

    {{-- Current stock summary --}}
    <div class="mb-6">
        <div class="inline-block bg-white rounded-xl shadow px-6 py-4">
            <div class="text-sm text-gray-500 mb-1">Current Stock</div>
            <div class="text-3xl font-bold {{ $ingredient->isLowStock() ? 'text-red-500' : 'text-green-600' }}">
                {{ $ingredient->current_stock }} {{ $ingredient->unit }}
            </div>
            @if($ingredient->isLowStock())
                <div class="text-xs text-red-400 mt-1">⚠️ Below minimum ({{ $ingredient->minimum_stock }} {{ $ingredient->unit }})</div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-left">Date & Time</th>
                <th class="px-6 py-4 text-left">Type</th>
                <th class="px-6 py-4 text-left">Quantity</th>
                <th class="px-6 py-4 text-left">Order</th>
                <th class="px-6 py-4 text-left">Notes</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse($movements as $m)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500">{{ $m->created_at->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-4">
                        @if($m->type === 'manual_add')
                            <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">Stock In</span>
                        @else
                            <span class="bg-red-100 text-red-600 text-xs font-semibold px-2 py-1 rounded-full">Deduction</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold {{ $m->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }} {{ $ingredient->unit }}
                    </td>
                    <td class="px-6 py-4">
                        @if($m->order)
                            <a href="{{ route('orders.show', $m->order) }}"
                               class="text-blue-600 hover:underline font-medium">
                                #{{ $m->order_id }}
                            </a>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $m->notes ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400">No movements recorded yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $movements->links() }}</div>
@endsection
