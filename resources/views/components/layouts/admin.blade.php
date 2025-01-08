<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'REMS'))</title>

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
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <img src="{{ asset('images/brac.png') }}" alt="BRAC Logo" class="h-8 w-auto">
                        <h2 class="ml-4 text-xl font-semibold text-gray-800">Admin Dashboard</h2>
                    </div>

                    <!-- User Dropdown -->
                    <div class="flex items-center">
                        <div class="relative">
                            <button type="button" class="flex items-center gap-2 bg-white p-2 rounded-lg">
                                <span class="text-gray-700">{{ Auth::user()->name }}</span>
                                <img src="{{ asset('images/brac.png') }}" alt="Profile" class="h-8 w-8 rounded-full">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar and Main Content -->
        <div class="flex">
            <!-- Admin Sidebar -->
            <div class="w-64 min-h-screen bg-[#4834d4] text-white">
                <div class="p-6 flex items-center">
                    <img src="{{ asset('images/brac.png') }}" alt="BRAC Logo" class="h-12 w-auto mr-4">
                    <h1 class="text-2xl font-bold">ADMIN DASHBOARD</h1>
                </div>
                <nav class="mt-6 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('admin.dashboard') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Venues -->
                    <a href="{{ route('admin.venues.index') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('admin.venues.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Venues
                    </a>

                    <!-- Clubs -->
                    <a href="{{ route('admin.clubs.index') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('admin.clubs.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Clubs
                    </a>

                    <!-- Events
                    <a href="{{ route('admin.events.index') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('admin.events.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Events
                    </a>

                    Users
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('admin.users.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Users
                    </a>

                    System Logs
                    <a href="{{ route('admin.logs.index') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('admin.logs.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        System Logs
                    </a> -->

                    <!-- Home -->
                    <a href="{{ route('welcome.page') }}" 
                           class="flex items-center px-6 py-3 text-white hover:bg-[#686de0]">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Home
                    </a>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center px-6 py-3 text-white hover:bg-[#686de0]">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex-1 p-8">
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
