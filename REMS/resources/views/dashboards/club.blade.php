@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Club Dashboard</h1>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Events</h3>
            <p class="text-3xl font-bold text-indigo-600">{{ $eventStats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Approved Events</h3>
            <p class="text-3xl font-bold text-green-600">{{ $eventStats['approved'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Pending Events</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $eventStats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Members</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $totalMembers }}</p>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">Upcoming Events</h2>
            @if($upcomingEvents->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($upcomingEvents as $event)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $event->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $event->venue->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $event->start_date->format('M d, Y h:i A') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($event->status === 'approved') bg-green-100 text-green-800
                                            @elseif($event->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </td>
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

    <!-- Quick Actions and Member Management -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
            <div class="space-y-4">
                <a href="{{ route('events.create') }}" class="block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-center">
                    Book New Event
                </a>
                <a href="{{ route('members.import') }}" class="block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-center">
                    Import Members
                </a>
                <a href="{{ route('members.export') }}" class="block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-center">
                    Export Members List
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Recent Members</h2>
            @if($recentMembers->count() > 0)
                <div class="space-y-4">
                    @foreach($recentMembers as $member)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">{{ $member->name }}</span>
                            <span class="text-sm text-gray-500">{{ $member->pivot->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('members.index') }}" class="text-indigo-600 hover:text-indigo-900">View All Members →</a>
                </div>
            @else
                <p class="text-gray-500">No recent members.</p>
            @endif
        </div>
    </div>
</div>
@endsection
