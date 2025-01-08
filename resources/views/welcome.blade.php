@php
    use Illuminate\Support\Facades\Route;
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
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-indigo-100 to-white">
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur-sm border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <img src="{{ asset('images/brac.png') }}" alt="BRAC Logo" class="h-16">
                        </div>
                    </div>
                    <!-- Login/Dashboard Button -->
                    <div class="flex items-center">
                        @if (Route::has('login'))
                            <div class="p-6 text-right">
                                @auth
                                    @if(auth()->user()->hasRole('oca'))
                                        <a href="{{ route('oca.dashboard') }}" class="font-semibold text-white bg-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-700">Dashboard</a>
                                    @elseif(auth()->user()->hasRole(['bucc', 'buac', 'robu', 'bizbee']))
                                        <a href="{{ route('club.dashboard') }}" class="font-semibold text-white bg-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-700">Dashboard</a>
                                    @elseif(auth()->user()->hasRole('admin'))
                                        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-white bg-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-700">Dashboard</a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="font-semibold text-white bg-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-700">Log in</a>

                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block">Welcome to</span>
                    <span class="block text-indigo-600">Reservation & Event Management System</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Streamline your event planning and room booking process with BRAC University's comprehensive management system.
                </p>
                <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                    <div class="rounded-md shadow">
                        @auth
                            @if(auth()->user()->hasRole('oca'))
                                <a href="{{ route('oca.dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                    Back to Dashboard
                                </a>
                            @elseif(auth()->user()->hasRole('club'))
                                <a href="{{ route('club.dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                    Back to Dashboard
                                </a>
                            @elseif(auth()->user()->hasRole('admin'))
                                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                    Back to Dashboard
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                Get Started
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-12 bg-white/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Feature 1 -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900">Event Management</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Plan and manage your events with ease. Get approvals, track status, and manage attendees.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900">Room Booking</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Find and book available rooms for your events. Check schedules and facilities.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900">Club Management</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Manage club activities, members, and events all in one place.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
