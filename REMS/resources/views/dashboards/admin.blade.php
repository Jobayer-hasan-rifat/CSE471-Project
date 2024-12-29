@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

    <!-- System Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Users</h3>
            <p class="text-3xl font-bold text-indigo-600">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Active Clubs</h3>
            <p class="text-3xl font-bold text-green-600">{{ $activeClubs }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Events</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $totalEvents }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Venues</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $totalVenues }}</p>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">Recent Activity</h2>
            @if($recentActivity->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentActivity as $activity)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $activity->action }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $activity->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $activity->details }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $activity->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No recent activity.</p>
            @endif
        </div>
    </div>

    <!-- System Management -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
            <div class="space-y-4">
                <a href="{{ route('users.index') }}" class="block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-center">
                    Manage Users
                </a>
                <a href="{{ route('clubs.index') }}" class="block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-center">
                    Manage Clubs
                </a>
                <a href="{{ route('venues.index') }}" class="block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-center">
                    Manage Venues
                </a>
                <a href="{{ route('reports.system') }}" class="block px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 text-center">
                    System Reports
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">System Status</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">System Status</span>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Operational</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Last Backup</span>
                    <span class="text-sm text-gray-500">{{ $lastBackup }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Storage Usage</span>
                    <span class="text-sm text-gray-500">{{ $storageUsage }}%</span>
                </div>
                <div class="mt-6">
                    <a href="{{ route('system.logs') }}" class="text-indigo-600 hover:text-indigo-900">View System Logs →</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
