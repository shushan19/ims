@extends('layouts.app')
@section('title', 'New Order')

@section('content')

    <div class="max-w-2xl">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">🧾 New Order</h1>

        <div class="bg-white rounded-xl shadow p-6">
            <form action="{{ route('orders.store') }}" method="POST" class="space-y-5" id="orderForm">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Order Items</label>

                    <div id="items-container" class="space-y-3">
                        <div class="item-row flex gap-3 items-center">
                            <select name="items[0][menu_item_id]"
                                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                <option value="">Select menu item...</option>
                                @foreach($menuItems->groupBy('category.name') as $categoryName => $items)
                                    <optgroup label="{{ $categoryName }}">
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }} — Rs.{{ number_format($item->price, 2) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>

                            <input type="number" name="items[0][quantity]"
                                   value="1" min="1"
                                   class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>

                            <button type="button" disabled
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 text-gray-300 cursor-not-allowed text-lg">
                                ✕
                            </button>
                        </div>
                    </div>

                    <button type="button" id="addItem"
                            class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium transition">
                        + Add another item
                    </button>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                    <textarea name="notes" rows="2"
                              placeholder="Special instructions..."
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2 rounded-lg transition">
                        Place Order
                    </button>
                    <a href="{{ route('orders.index') }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-6 py-2 rounded-lg transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let rowIndex = 1;
        const optionsHtml = `
    @foreach($menuItems->groupBy('category.name') as $categoryName => $items)
        <optgroup label="{{ $categoryName }}">
            @foreach($items as $item)
        <option value="{{ $item->id }}">{{ $item->name }} — ${{ number_format($item->price, 2) }}</option>
            @endforeach
        </optgroup>
    @endforeach
        `;

        document.getElementById('addItem').addEventListener('click', function () {
            const container = document.getElementById('items-container');

            const row = document.createElement('div');
            row.className = 'item-row flex gap-3 items-center';
            row.innerHTML = `
        <select name="items[${rowIndex}][menu_item_id]"
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
            <option value="">Select menu item...</option>
            ${optionsHtml}
        </select>
        <input type="number" name="items[${rowIndex}][quantity]"
               value="1" min="1"
               class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500"
               required>
        <button type="button"
                class="remove-btn w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-500 text-lg transition">
            ✕
        </button>
    `;

            // Attach remove handler
            row.querySelector('.remove-btn').addEventListener('click', () => row.remove());

            container.appendChild(row);
            rowIndex++;
        });
    </script>
@endpush
