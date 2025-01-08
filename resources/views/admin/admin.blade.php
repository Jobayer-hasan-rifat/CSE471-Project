@extends('components.layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Main Content -->
<div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
    <!-- Dashboard Content -->
    <main class="p-6">
        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Users</h3>
                <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $totalUsers }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Clubs</h3>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $totalClubs }}</p>
                <div class="mt-2 text-sm">
                    <span class="text-green-500">{{ $activeClubs }} active</span> /
                    <span class="text-red-500">{{ $inactiveClubs }} inactive</span>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Venues</h3>
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $totalVenues }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Events</h3>
                <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $totalEvents }}</p>
                <div class="mt-2 text-sm">
                    <span class="text-yellow-500">{{ $pendingEvents }} pending</span> /
                    <span class="text-green-500">{{ $approvedEvents }} approved</span>
                </div>
            </div>
        </div>

        <!-- Event Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Event Status</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Pending Events</span>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">{{ $pendingEvents }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Approved Events</span>
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">{{ $approvedEvents }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Rejected Events</span>
                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full">{{ $rejectedEvents }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Total Budget Approved</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">৳{{ number_format($totalBudget) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Recent Events</h3>
                @if($recentEvents->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentEvents as $event)
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $event->title }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $event->club->name }}</p>
                                </div>
                                <span class="px-2 py-1 text-sm 
                                    @if($event->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($event->status === 'approved') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif rounded-full">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No recent events</p>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                    View Dashboard
                </a>
                <a href="{{ route('admin.events.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                    Manage Events
                </a>
                <a href="{{ route('admin.venues.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Manage Venues
                </a>
            </div>
        </div>
    </main>
</div>
@endsection