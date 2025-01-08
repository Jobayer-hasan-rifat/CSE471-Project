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

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                        <h2 class="ml-4 text-xl font-semibold text-gray-800">OCA Dashboard</h2>
                    </div>
                    <!-- User Dropdown -->
                    <div class="flex items-center">
                        <div class="relative">
                            <button type="button" class="flex items-center gap-2 bg-white p-2 rounded-lg">
                                <span class="text-gray-700">{{ Auth::user()->name }}</span>
                                <img src="{{ asset('images/oca.png') }}" alt="Profile" class="h-8 w-8 rounded-full">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar and Main Content -->
        <div class="flex">
            <!-- OCA Sidebar -->
            <div class="w-64 min-h-screen bg-[#4834d4] text-white">
                <!-- OCA Logo/Title -->
                <div class="p-6 flex flex-col items-center">
                    <div class="shrink-0 flex items-center">
                        <img src="{{ asset('images/oca.png') }}" alt="OCA Logo" class="h-8 w-auto">
                        <span class="ml-2 text-lg font-bold">OCA</span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="mt-6 space-y-1">
                    <a href="{{ route('oca.dashboard') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('oca.dashboard') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('oca.transactions.index') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('oca.transactions.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Transactions
                    </a>

                    <a href="{{ route('oca.announcements.index') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('oca.announcements.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        Announcements
                    </a>

                    <a href="{{ route('oca.events.pending') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('oca.events.pending') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Pending Events
                    </a>
                    
                    <a href="{{ route('oca.calendar.index') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('oca.calendar.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Calendar
                    </a>
                    
                    <a href="{{ route('oca.clubs.index') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('oca.clubs.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Club Information
                    </a>

                    <a href="{{ route('oca.chat.index') }}" 
                       class="flex items-center px-6 py-3 text-white {{ request()->routeIs('oca.chat.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        Chat
                    </a>

                    <div class="mt-auto">
                        <!-- Home Button -->
                        <a href="{{ route('welcome.page') }}" 
                           class="flex items-center px-6 py-3 text-white hover:bg-[#686de0]">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Home
                        </a>
                        
                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-6 py-3 text-white hover:bg-[#686de0]">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex-1">
                @isset($content)
                    {{ $content }}
                @else
                    @yield('content')
                @endisset
            </div>
        </div>
    </div>

    @stack('scripts')
    @push('scripts')
    <script>
    function loadPendingEvents() {
        fetch('{{ route('oca.events.pending') }}')
            .then(response => response.text())
            .then(html => {
                document.querySelector('.flex-1').innerHTML = html;
            })
            .catch(error => console.error('Error:', error));
    }
    </script>
    @endpush
</body>
</html>
