<header class="bg-white border-b sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tight">
            DriveSpot Auto
        </a>

        <nav class="flex items-center gap-6 text-sm font-medium">
            <a href="{{ route('home') }}"
               class="{{ request()->routeIs('home') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">
                Home
            </a>

            <a href="{{ route('shop') }}"
               class="{{ request()->routeIs('shop') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">
                Shop
            </a>

            <a href="{{ route('cart') }}"
               class="{{ request()->routeIs('cart') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">
                Cart
            </a>

            <a href="{{ route('checkout') }}"
               class="{{ request()->routeIs('checkout') ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">
                Checkout
            </a>
        </nav>
    </div>
</header>