<div class="bg-white rounded-xl">

    <div class="px-4 py-4">
        <h3 class="text-lg font-semibold text-gray-900">
            Categories
        </h3>
    </div>

    <div class="px-4 pb-4">
        <input
            type="text"
            placeholder="Search for a category"
            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-gray-400"
        >
    </div>

    <nav class="space-y-1">
        @forelse($categories ?? [] as $category)
            <div x-data="{ open: false }" class="px-3">

                @if($category->children->count())
                    <div class="rounded-lg overflow-hidden">
                        <button
                            type="button"
                            @click="open = !open"
                            class="w-full flex items-center justify-between px-3 py-3 text-sm text-gray-800 hover:bg-gray-50 transition"
                            :class="open ? 'bg-gray-50 font-semibold' : ''"
                        >
                            <span>{{ $category->name }}</span>
                            <span class="text-gray-400" x-text="open ? '-' : '+'"></span>
                        </button>

                        <div
                            x-show="open"
                            x-cloak
                            class="ml-3 border-l border-gray-200 bg-gray-50/60"
                        >
                            @foreach($category->children as $child)
                                <a
                                    href="{{ route('shop', ['category' => $child->slug]) }}"
                                    class="block px-4 py-2.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition"
                                >
                                    {{ $child->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a
                        href="{{ route('shop', ['category' => $category->slug]) }}"
                        class="flex items-center justify-between px-3 py-3 text-sm text-gray-800 hover:bg-gray-50 rounded-lg transition"
                    >
                        <span>{{ $category->name }}</span>
                    </a>
                @endif

            </div>
        @empty
            <div class="px-4 py-4 text-sm text-gray-500">
                No categories available.
            </div>
        @endforelse
    </nav>

</div>