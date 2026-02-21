@extends('layouts.app')
@section('title', 'Ingredients')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Ingredients / Stock</h1>
        <a href="{{ route('ingredients.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            + Add Ingredient
        </a>
    </div>

    @if($lowStock->isNotEmpty())
        <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            ⚠️ <strong>Low Stock:</strong> {{ $lowStock->pluck('name')->join(', ') }} {{ $lowStock->count() === 1 ? 'is' : 'are' }} below minimum level.
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-left">Ingredient</th>
                <th class="px-6 py-4 text-left">Unit</th>
                <th class="px-6 py-4 text-left">Current Stock</th>
                <th class="px-6 py-4 text-left">Min. Stock</th>
                <th class="px-6 py-4 text-left">Status</th>
                <th class="px-6 py-4 text-left">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse($ingredients as $ingredient)
                <tr class="hover:bg-gray-50 {{ $ingredient->isLowStock() ? 'bg-red-50' : '' }}">
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $ingredient->name }}</td>
                    <td class="px-6 py-4 text-gray-500 uppercase">{{ $ingredient->unit }}</td>
                    <td class="px-6 py-4 font-medium {{ $ingredient->isLowStock() ? 'text-red-600' : 'text-gray-800' }}">
                        {{ $ingredient->current_stock }}
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $ingredient->minimum_stock }}</td>
                    <td class="px-6 py-4">
                        @if($ingredient->isLowStock())
                            <span class="bg-red-100 text-red-600 text-xs font-semibold px-2 py-1 rounded-full">Low Stock</span>
                        @else
                            <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">OK</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2 flex-wrap">
                            <button onclick="document.getElementById('stockForm{{ $ingredient->id }}').classList.toggle('hidden')"
                                    class="bg-green-50 hover:bg-green-100 text-green-700 text-xs font-medium px-3 py-1.5 rounded-lg transition">
                                + Stock In
                            </button>

                            <a href="{{ route('ingredients.movements', $ingredient) }}"
                               class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium px-3 py-1.5 rounded-lg transition">
                                History
                            </a>

                            <a href="{{ route('ingredients.edit', $ingredient) }}"
                               class="bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1.5 rounded-lg transition">
                                Edit
                            </a>

                            <form action="{{ route('ingredients.destroy', $ingredient) }}" method="POST"
                                  onsubmit="return confirm('Delete ingredient?')">
                                @csrf @method('DELETE')
                                <button class="bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium px-3 py-1.5 rounded-lg transition">
                                    Delete
                                </button>
                            </form>
                        </div>

                        {{-- Inline Stock In form (hidden by default) --}}
                        <div id="stockForm{{ $ingredient->id }}" class="hidden mt-3">
                            <form action="{{ route('ingredients.add-stock', $ingredient) }}" method="POST"
                                  class="flex gap-2 items-center">
                                @csrf
                                <input type="number" name="quantity" step="0.001" min="0.001"
                                       placeholder="Qty ({{ $ingredient->unit }})"
                                       class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs w-36 focus:outline-none focus:ring-2 focus:ring-green-400"
                                       required>
                                <input type="text" name="notes"
                                       placeholder="Note (optional)"
                                       class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs w-40 focus:outline-none focus:ring-2 focus:ring-green-400">
                                <button class="bg-green-600 hover:bg-green-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition">
                                    Add
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">No ingredients yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $ingredients->links() }}</div>
@endsection
