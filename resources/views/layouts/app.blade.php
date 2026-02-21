
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Restaurant IMS')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-56 bg-gray-900 text-gray-300 flex flex-col p-4 fixed h-full">
        <div class="text-white font-bold text-lg mb-8 flex items-center gap-2">
            🍽️ Restaurant IMS
        </div>

        <nav class="flex flex-col gap-1">
            <a href="{{ route('dashboard') }}"
               class="px-3 py-2 rounded-md text-sm hover:bg-gray-700 hover:text-white transition
                      {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : '' }}">
                📊 Dashboard
            </a>
            <a href="{{ route('categories.index') }}"
               class="px-3 py-2 rounded-md text-sm hover:bg-gray-700 hover:text-white transition
                      {{ request()->routeIs('categories.*') ? 'bg-gray-700 text-white' : '' }}">
                🏷️ Categories
            </a>
            <a href="{{ route('menu-items.index') }}"
               class="px-3 py-2 rounded-md text-sm hover:bg-gray-700 hover:text-white transition
                      {{ request()->routeIs('menu-items.*') ? 'bg-gray-700 text-white' : '' }}">
                🍔 Menu Items
            </a>
            <a href="{{ route('ingredients.index') }}"
               class="px-3 py-2 rounded-md text-sm hover:bg-gray-700 hover:text-white transition
                      {{ request()->routeIs('ingredients.*') ? 'bg-gray-700 text-white' : '' }}">
                📦 Ingredients
            </a>
            <a href="{{ route('orders.index') }}"
               class="px-3 py-2 rounded-md text-sm hover:bg-gray-700 hover:text-white transition
                      {{ request()->routeIs('orders.*') ? 'bg-gray-700 text-white' : '' }}">
                🧾 Orders
            </a>
        </nav>
    </aside>

    <main class="ml-56 flex-1 p-8">

        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 px-4 py-3 bg-red-100 border border-red-300 text-red-800 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 px-4 py-3 bg-red-100 border border-red-300 text-red-800 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
