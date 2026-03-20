<div class="group bg-white rounded-lg p-6 min-h-[190px] flex flex-col justify-center items-center text-center border transition duration-300 hover:shadow-lg hover:-translate-y-1 {{ $active ? 'border-gray-900 ring-1 ring-gray-900' : 'border-gray-200 hover:border-gray-300' }}">

    <div class="h-32 flex items-center justify-center mb-5">
        @if($category->image)
            <img
                src="{{ asset('storage/'.$category->image) }}"
                alt="{{ $category->name }}"
                class="max-h-20 object-contain transition duration-300 group-hover:scale-110"
            >
        @endif
    </div>

    <div class="text-sm font-medium text-gray-800 group-hover:text-orange-600 transition duration-300">
        {{ $category->name }}
    </div>
</div>