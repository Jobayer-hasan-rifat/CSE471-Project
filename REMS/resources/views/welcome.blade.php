<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>REMS - Resource and Event Management System</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gradient-to-br from-indigo-100 to-white">
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur-sm border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-indigo-600">REMS</span>
                    </div>
                    <div class="flex items-center">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-indigo-600">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-indigo-600">Log in</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block">Resource and Event</span>
                    <span class="block text-indigo-600">Management System</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Streamline your university club's event planning and resource management with our comprehensive system.
                </p>
                <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                    @auth
                        <div class="rounded-md shadow">
                            <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                Go to Dashboard
                            </a>
                        </div>
                    @else
                        <div class="rounded-md shadow">
                            <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                Get Started
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-12 bg-white/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Feature 1 -->
                    <div class="p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-lg font-semibold text-indigo-600 mb-3">Event Management</div>
                        <p class="text-gray-600">Schedule and manage events with ease. Check venue availability and handle bookings efficiently.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-lg font-semibold text-indigo-600 mb-3">Resource Allocation</div>
                        <p class="text-gray-600">Efficiently allocate and track resources for your events and activities.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-lg font-semibold text-indigo-600 mb-3">Club Management</div>
                        <p class="text-gray-600">Manage club members, track activities, and maintain documentation all in one place.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white/80 border-t border-gray-100">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-500 text-sm">
                    {{ date('Y') }} REMS. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
