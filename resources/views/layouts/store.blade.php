<!DOCTYPE html>
<html lang="en">
    
<meta name="tailwind-test-layout" content="store-layout-loaded">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'DriveSpot Auto' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-900">

    <div class="min-h-screen flex flex-col">
        @include('store.partials.header')

        <main class="flex-1">
            @yield('content')
        </main>

        @include('store.partials.footer')
    </div>

</body>
</html>