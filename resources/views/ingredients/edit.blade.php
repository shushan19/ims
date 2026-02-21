@extends('layouts.app')
@section('title', 'Edit Ingredient')

@section('content')

    <div class="max-w-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">✏️ Edit Ingredient</h1>

        <div class="bg-white rounded-xl shadow p-6">
            <form action="{{ route('ingredients.update', $ingredient) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name"
                           value="{{ old('name', $ingredient->name) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                    <input type="text"
                           value="{{ strtoupper($ingredient->unit) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm bg-gray-50 text-gray-400 cursor-not-allowed"
                           readonly>
                    <p class="text-gray-400 text-xs mt-1">Unit cannot be changed after creation.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Stock</label>
                    <input type="text"
                           value="{{ $ingredient->current_stock }} {{ $ingredient->unit }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm bg-gray-50 text-gray-400 cursor-not-allowed"
                           readonly>
                    <p class="text-gray-400 text-xs mt-1">Use "Stock In" on the ingredients list to update stock.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Stock Alert Level</label>
                    <input type="number" name="minimum_stock"
                           value="{{ old('minimum_stock', $ingredient->minimum_stock) }}"
                           step="0.001" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                        Update
                    </button>
                    <a href="{{ route('ingredients.index') }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-5 py-2 rounded-lg transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
