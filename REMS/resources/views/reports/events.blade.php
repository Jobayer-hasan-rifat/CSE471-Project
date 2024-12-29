@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css">
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Event Reports</h1>

    <!-- Date Range Filter -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form action="{{ route('reports.events') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date', now()->subMonth()->format('Y-m-d')) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Apply Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Events</h3>
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Approved Events</h3>
            <p class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Pending Events</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">Rejected Events</h3>
            <p class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Events by Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Events by Status</h3>
            <canvas id="statusChart"></canvas>
        </div>

        <!-- Events by Month -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Events by Month</h3>
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    <!-- Venue Usage -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Venue Usage</h3>
        <canvas id="venueChart"></canvas>
    </div>

    <!-- Recent Events Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Recent Events</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentEvents as $event)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $event->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $event->club->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $event->venue->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $event->start_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($event->status === 'approved') bg-green-100 text-green-800
                                        @elseif($event->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $recentEvents->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status Chart
    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: ['Approved', 'Pending', 'Rejected'],
            datasets: [{
                data: [
                    {{ $stats['approved'] }},
                    {{ $stats['pending'] }},
                    {{ $stats['rejected'] }}
                ],
                backgroundColor: ['#10B981', '#F59E0B', '#EF4444']
            }]
        }
    });

    // Monthly Chart
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyStats->pluck('month')) !!},
            datasets: [{
                label: 'Number of Events',
                data: {!! json_encode($monthlyStats->pluck('count')) !!},
                backgroundColor: '#6366F1'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Venue Usage Chart
    new Chart(document.getElementById('venueChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($venueStats->pluck('name')) !!},
            datasets: [{
                label: 'Number of Events',
                data: {!! json_encode($venueStats->pluck('count')) !!},
                backgroundColor: '#8B5CF6'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>
@endsection
