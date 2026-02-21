@extends('layouts.app')
@section('title', 'Menu Items')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Menu Items</h1>
        <a href="{{ route('menu-items.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            + Add Menu Item
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-left">Name</th>
                <th class="px-6 py-4 text-left">Category</th>
                <th class="px-6 py-4 text-left">Price</th>
                <th class="px-6 py-4 text-left">Available</th>
                <th class="px-6 py-4 text-left">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse($menuItems as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $item->name }}</td>
                    <td class="px-6 py-4">
                    <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2 py-1 rounded-full">
                        {{ $item->category->name }}
                    </span>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-700">${{ number_format($item->price, 2) }}</td>
                    <td class="px-6 py-4">
                        @if($item->is_available)
                            <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">Yes</span>
                        @else
                            <span class="bg-gray-100 text-gray-500 text-xs font-semibold px-2 py-1 rounded-full">No</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('recipes.show', $item) }}"
                           class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium px-3 py-1.5 rounded-lg transition">
                            Recipe
                        </a>
                        <a href="{{ route('menu-items.edit', $item) }}"
                           class="bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1.5 rounded-lg transition">
                            Edit
                        </a>
                        <form action="{{ route('menu-items.destroy', $item) }}" method="POST"
                              onsubmit="return confirm('Delete this menu item?')">
                            @csrf @method('DELETE')
                            <button class="bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium px-3 py-1.5 rounded-lg transition">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400">No menu items yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $menuItems->links() }}</div>
@endsection
