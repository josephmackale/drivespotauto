@if(request()->hasAny(['search','engine','category','brand']))

<div class="mb-6 flex flex-wrap items-center gap-2">

    <span class="text-sm text-gray-500">
        Active filters:
    </span>

    @if(request('search'))
        <span class="px-3 py-1 text-sm bg-gray-100 rounded-full">
            Search: {{ request('search') }}
        </span>
    @endif

    @if(request('engine'))
        <span class="px-3 py-1 text-sm bg-gray-100 rounded-full">
            Vehicle engine: {{ request('engine') }}
        </span>
    @endif

    @if(request('category'))
        <span class="px-3 py-1 text-sm bg-gray-100 rounded-full">
            Category
        </span>
    @endif

    <a
        href="{{ route('shop') }}"
        class="ml-2 text-sm text-gray-600 hover:text-black underline"
    >
        Clear filters
    </a>

</div>

@endif