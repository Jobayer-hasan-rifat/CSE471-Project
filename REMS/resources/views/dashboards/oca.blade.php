@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">OCA Dashboard</h1>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Pending Events</h3>
            <p class="text-3xl font-bold text-indigo-600">{{ $pendingEvents }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Approved Events</h3>
            <p class="text-3xl font-bold text-green-600">{{ $approvedEvents }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Venues</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $totalVenues }}</p>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">Upcoming Events</h2>
            @if($upcomingEvents->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($upcomingEvents as $event)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $event->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $event->club->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $event->venue->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $event->start_date->format('M d, Y h:i A') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No upcoming events.</p>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
            <div class="space-y-4">
                <a href="{{ route('events.pending') }}" class="block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-center">
                    Review Pending Events
                </a>
                <a href="{{ route('venues.create') }}" class="block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-center">
                    Add New Venue
                </a>
                <a href="{{ route('reports.events') }}" class="block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-center">
                    View Reports
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">System Status</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Active Venues</span>
                    <span class="text-green-600 font-semibold">{{ $totalVenues }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Events This Month</span>
                    <span class="text-blue-600 font-semibold">{{ $approvedEvents }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
