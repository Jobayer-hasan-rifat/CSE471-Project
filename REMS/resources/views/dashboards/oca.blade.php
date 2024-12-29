@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Profile Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-indigo-700 rounded-full flex items-center justify-center">
                <span class="text-2xl font-bold text-white">OCA</span>
            </div>
            <div>
                <h2 class="text-xl font-bold">Office of Co-curricular Activities</h2>
                <p class="text-gray-600">oca@bracu.ac.bd</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Clubs -->
        <a href="{{ route('oca.clubs.index') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Total Clubs</p>
                    <h3 class="text-2xl font-bold">{{ $totalClubs ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-purple-600"></i>
                </div>
            </div>
        </a>

        <!-- Total Events -->
        <a href="{{ route('oca.events.index') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Total Events</p>
                    <h3 class="text-2xl font-bold">{{ $totalEvents ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar text-indigo-600"></i>
                </div>
            </div>
        </a>

        <!-- Pending Events -->
        <!-- <a href="{{ route('oca.events.pending') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Pending Events</p>
                    <h3 class="text-2xl font-bold">{{ $pendingEvents ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
        </a> -->

        <!-- Total Venues -->
        <a href="{{ route('oca.venues.index') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Total Venues</p>
                    <h3 class="text-2xl font-bold">{{ $totalVenues ?? 0 }}</h3>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-green-600"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent and Upcoming Events -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Recent Events -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Recent Events</h2>
                <a href="{{ route('oca.events.index') }}" class="text-indigo-600 hover:text-indigo-800">View All</a>
            </div>
            @if(isset($recentEvents) && $recentEvents->count() > 0)
                <div class="space-y-4">
                    @foreach($recentEvents as $event)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-calendar text-indigo-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold">{{ $event->title }}</h3>
                                    <p class="text-sm text-gray-600">
                                        {{ $event->club->name }} • 
                                        {{ $event->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-sm rounded-full 
                                @if($event->status === 'approved') bg-green-100 text-green-800
                                @elseif($event->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No recent events</p>
            @endif
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Upcoming Events</h2>
                <a href="{{ route('oca.events.index') }}" class="text-indigo-600 hover:text-indigo-800">View All</a>
            </div>
            @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingEvents as $event)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-calendar text-indigo-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold">{{ $event->title }}</h3>
                                    <p class="text-sm text-gray-600">
                                        {{ $event->club->name }} • 
                                        {{ $event->start_date->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-sm rounded-full 
                                @if($event->status === 'approved') bg-green-100 text-green-800
                                @elseif($event->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No upcoming events</p>
            @endif
        </div>
    </div>
</div>
@endsection