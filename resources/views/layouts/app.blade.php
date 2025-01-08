@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'REMS') }}</title>
     <!-- Test Image -->
    <img src="{{ asset('images/brac.png') }}" alt="Test Image" style="width: 32px; height: 32px;">
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/brac.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Additional Styles -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @if(auth()->user()->hasRole('oca'))
            @include('oca.navigation')
            <div class="flex">
                @include('oca.sidebar')
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        @elseif(auth()->user()->hasRole(['bucc', 'buac', 'robu', 'bizbee']))
            @include('club.navigation')
            <div class="flex">
                @include('club.sidebar')
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        @elseif(auth()->user()->hasRole('admin'))
            @include('admin.navigation')
            <div class="flex">
                @include('admin.sidebar')
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        @endif
    </div>

    @stack('scripts')
</body>
</html>
