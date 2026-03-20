<div class="mb-6 flex items-center justify-between">

    <div class="text-sm text-gray-600">
        Showing
        <span class="font-medium">{{ $products->firstItem() }}</span>
        to
        <span class="font-medium">{{ $products->lastItem() }}</span>
        of
        <span class="font-medium">{{ $products->total() }}</span>
        results
    </div>

    <div>
        <form method="GET" action="{{ route('shop') }}">

            @foreach(request()->except('sort') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            <select
                name="sort"
                onchange="this.form.submit()"
                class="rounded-xl border-gray-300 text-sm"
            >
                <option value="">Latest</option>
                <option value="price_low" {{ request('sort')=='price_low'?'selected':'' }}>
                    Price: Low → High
                </option>
                <option value="price_high" {{ request('sort')=='price_high'?'selected':'' }}>
                    Price: High → Low
                </option>
                <option value="name_asc" {{ request('sort')=='name_asc'?'selected':'' }}>
                    Name A → Z
                </option>
                <option value="name_desc" {{ request('sort')=='name_desc'?'selected':'' }}>
                    Name Z → A
                </option>
            </select>

        </form>
    </div>

</div>