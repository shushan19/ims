@extends('layouts.app')
@section('title', 'Add Ingredient')

@section('content')

    <div class="max-w-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">📦 Add Ingredient</h1>

        <div class="bg-white rounded-xl shadow p-6">
            <form action="{{ route('ingredients.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name') }}"
                           placeholder="e.g. Tomato"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                              {{ $errors->has('name') ? 'border-red-400' : '' }}">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Unit <span class="text-red-500">*</span>
                    </label>
                    <select name="unit"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select unit...</option>
                        @foreach(['kg', 'gram', 'piece', 'liter', 'ml'] as $unit)
                            <option value="{{ $unit }}" {{ old('unit') === $unit ? 'selected' : '' }}>
                                {{ strtoupper($unit) }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Current Stock <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="current_stock"
                               value="{{ old('current_stock', 0) }}"
                               step="0.001" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('current_stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Minimum Stock <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="minimum_stock"
                               value="{{ old('minimum_stock', 0) }}"
                               step="0.001" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('minimum_stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                        Save Ingredient
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
