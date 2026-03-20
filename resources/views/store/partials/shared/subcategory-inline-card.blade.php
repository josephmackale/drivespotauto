<a href="{{ $href }}"
   class="group flex items-center gap-3 rounded-lg px-3 py-3 border transition duration-200
   {{ !empty($active)
        ? 'bg-gray-50 border-gray-300 ring-1 ring-gray-200'
        : 'bg-white border-transparent hover:bg-gray-50 hover:border-gray-200' }}">

    <div class="w-12 h-12 flex items-center justify-center shrink-0 rounded-md overflow-hidden bg-white">
        @if(!empty($category->image))
            <img
                src="{{ asset('storage/'.$category->image) }}"
                alt="{{ $category->name }}"
                class="max-w-full max-h-full object-contain transition duration-200 group-hover:scale-105"
            >
        @else
            <div class="text-[10px] text-gray-400 text-center leading-tight">
                No Image
            </div>
        @endif
    </div>

    <div class="min-w-0">
        <div class="text-sm font-medium text-gray-900 leading-snug truncate">
            {{ $category->name }}
        </div>
    </div>

</a>