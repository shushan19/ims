@extends('layouts.app')
@section('title', 'Categories')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">🏷️ Categories</h1>
        <a href="{{ route('categories.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            + Add Category
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-left">#</th>
                <th class="px-6 py-4 text-left">Name</th>
                <th class="px-6 py-4 text-left">Description</th>
                <th class="px-6 py-4 text-left">Menu Items</th>
                <th class="px-6 py-4 text-left">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-400">{{ $category->id }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $category->description ?? '—' }}</td>
                    <td class="px-6 py-4">
                    <span class="bg-gray-100 text-gray-700 text-xs font-medium px-2 py-1 rounded-full">
                        {{ $category->menu_items_count }}
                    </span>
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('categories.edit', $category) }}"
                           class="bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1.5 rounded-lg transition">
                            Edit
                        </a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST"
                              onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button class="bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium px-3 py-1.5 rounded-lg transition">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400">No categories yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>
@endsection
