@php
    use Illuminate\Support\Facades\Auth;
@endphp

@props(['title' => config('app.name', 'REMS')])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                        <h2 class="ml-4 text-xl font-semibold text-gray-800">{{ auth()->user()->club ? auth()->user()->club->name : 'Club Dashboard' }}</h2>
                    </div>

                    <!-- User Dropdown -->
                    <div class="flex items-center">
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button" class="flex items-center gap-2 bg-white p-2 rounded-lg">
                                <span class="text-gray-700">{{ Auth::user()->name }}</span>
                                <img src="{{ auth()->user()->club ? (auth()->user()->club->logo_url ?? asset('images/default-club-logo.png')) : asset('images/default-club-logo.png') }}" alt="Profile" class="h-8 w-8 rounded-full">
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex">
            <!-- Club Sidebar -->
            <div class="w-64 min-h-screen bg-[#4834d4] text-white">
                <!-- Club Logo and Name -->
                <div class="p-6 flex flex-col items-center">
                    @if(auth()->user()->club)
                        <img src="{{ asset('images/' . strtolower(auth()->user()->club->short_name) . '.png') }}" 
                             alt="{{ auth()->user()->club->name }}" 
                             class="w-24 h-24 rounded-full object-cover bg-white p-1">
                        <h2 class="mt-4 text-lg font-semibold text-center">{{ auth()->user()->club->name }}</h2>
                        <p class="text-sm text-gray-300">{{ auth()->user()->club->short_name }}</p>
                    @else
                        <div class="w-24 h-24 rounded-full bg-white flex items-center justify-center">
                            <img src="{{ asset('images/brac.png') }}" alt="BRAC Logo" class="h-16 w-16">
                        </div>
                        <h2 class="mt-4 text-lg font-semibold">Club Dashboard</h2>
                    @endif
                </div>

                <nav class="mt-6 space-y-1">
                    <a href="{{ route('welcome.page') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('welcome.page') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Home
                    </a>

                    <a href="{{ route('club.dashboard') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('club.dashboard') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('club.information') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('club.information') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Club Information
                    </a>

                    <a href="{{ route('club.calendar.index') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('club.calendar.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Calendar
                    </a>

                    <a href="{{ route('club.events.index') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('club.events.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                        Events
                    </a>

                    <a href="{{ route('club.transactions.index') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('club.transactions.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Transactions
                    </a>

                    <a href="{{ route('club.announcements.index') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('club.announcements.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        Announcements
                    </a>

                    <a href="{{ route('club.chat.index') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('club.chat.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        Chat
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-6 py-3 text-white hover:bg-[#686de0]">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex-1">
                {{ $content ?? $slot }}
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
