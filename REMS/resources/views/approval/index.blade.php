@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-indigo-900">Approval Request</h1>
            <p class="text-gray-600">Showing {{ $pendingEvents->count() }} of {{ $pendingEvents->total() }} requests</p>
        </div>
        <div class="relative">
            <input type="text" 
                   placeholder="Search requests..." 
                   class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
    </div>

    @if($pendingEvents->isEmpty())
    <div class="text-center py-12">
        <h2 class="text-xl font-semibold text-indigo-900 mb-2">No requests found</h2>
        <p class="text-gray-600">No pending requests at the moment</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b">
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Event Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Club</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Date</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Venue</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingEvents as $event)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $event->name }}</td>
                    <td class="px-6 py-4">{{ $event->club->name }}</td>
                    <td class="px-6 py-4">{{ $event->start_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">{{ $event->venue->name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-sm 
                            @if($event->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($event->status === 'approved') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($event->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button onclick="approveEvent({{ $event->id }})" 
                                class="text-green-600 hover:text-green-800">
                            <i class="fas fa-check"></i>
                        </button>
                        <button onclick="rejectEvent({{ $event->id }})" 
                                class="text-red-600 hover:text-red-800">
                            <i class="fas fa-times"></i>
                        </button>
                        <button onclick="viewDetails({{ $event->id }})" 
                                class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@push('scripts')
<script>
function approveEvent(eventId) {
    if (confirm('Are you sure you want to approve this event?')) {
        $.post(`/events/${eventId}/approve`, {
            _token: '{{ csrf_token() }}'
        }, function(response) {
            location.reload();
        });
    }
}

function rejectEvent(eventId) {
    if (confirm('Are you sure you want to reject this event?')) {
        $.post(`/events/${eventId}/reject`, {
            _token: '{{ csrf_token() }}'
        }, function(response) {
            location.reload();
        });
    }
}

function viewDetails(eventId) {
    window.location.href = `/events/${eventId}`;
}
</script>
@endpush
@endsection