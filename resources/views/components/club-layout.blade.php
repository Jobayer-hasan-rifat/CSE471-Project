<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'REMS') }}</title>

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
        <!-- Top Navigation -->
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <img src="{{ asset('images/bracu.png') }}" alt="BRAC Logo" class="h-8 w-auto">
                        </div>
                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <span class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900">
                                Club Dashboard
                            </span>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="flex items-center">
                        <div class="relative">
                            <button type="button" class="flex items-center gap-2 bg-white p-2 rounded-lg">
                                <span class="text-gray-700">{{ Auth::user()->name }}</span>
                                @php
                                    $user = Auth::user();
                                    $role = $user->getRoleNames()->first();
                                    $logoPath = 'images/' . strtolower($role) . '.png';
                                @endphp
                                <img src="{{ asset($logoPath) }}" alt="Profile" class="h-8 w-8 rounded-full">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        {{ $slot }}
    </div>

    @stack('scripts')
</body>
</html>
