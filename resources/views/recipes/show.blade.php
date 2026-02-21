@extends('layouts.app')
@section('title', 'Recipe: ' . $menuItem->name)

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Recipe: {{ $menuItem->name }}</h1>
        <a href="{{ route('menu-items.index') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition">
            ← Back
        </a>
    </div>

    <div class="grid grid-cols-2 gap-6">

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="bg-gray-800 text-white px-5 py-3 text-sm font-semibold">
                Current Ingredients
            </div>

            @if($menuItem->ingredients->isEmpty())
                <p class="text-gray-400 text-sm text-center py-10">No ingredients added yet.</p>
            @else
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3 text-left">Ingredient</th>
                        <th class="px-5 py-3 text-left">Qty / Serving</th>
                        <th class="px-5 py-3 text-left">Unit</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @foreach($menuItem->ingredients as $ingredient)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $ingredient->name }}</td>
                            <td class="px-5 py-3">
                                <form action="{{ route('recipes.update', [$menuItem, $ingredient]) }}" method="POST"
                                      class="flex gap-2 items-center">
                                    @csrf @method('PATCH')
                                    <input type="number" name="quantity_required"
                                           value="{{ $ingredient->pivot->quantity_required }}"
                                           step="0.001" min="0.001"
                                           class="border border-gray-300 rounded-lg px-2 py-1 text-xs w-24 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <button class="bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-medium px-2 py-1 rounded-lg transition">
                                        Save
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-3 text-gray-500 uppercase text-xs">{{ $ingredient->unit }}</td>
                            <td class="px-5 py-3">
                                <form action="{{ route('recipes.destroy', [$menuItem, $ingredient]) }}" method="POST"
                                      onsubmit="return confirm('Remove this ingredient?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:text-red-600 text-xs font-medium transition">
                                        Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Add New Ingredient to Recipe --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Add Ingredient to Recipe</h2>

            <form action="{{ route('recipes.store', $menuItem) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ingredient</label>
                    <select name="ingredient_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="">Select ingredient...</option>
                        @foreach($ingredients as $ing)
                            <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Quantity per 1 serving
                    </label>
                    <input type="number" name="quantity_required"
                           step="0.001" min="0.001"
                           placeholder="e.g. 50"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                    <p class="text-gray-400 text-xs mt-1">This is the amount used when making 1 of this item.</p>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                    Add to Recipe
                </button>
            </form>
        </div>

    </div>
@endsection
