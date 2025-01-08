@php
    use Illuminate\Support\Facades\Auth;
@endphp

<x-layouts.oca>
    <x-slot name="content">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold">Pending Event Requests</h1>
                <p class="mt-2 text-gray-600">Review and manage event requests from clubs</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($events as $event)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $event->event_name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($event->description, 100) }}</div>
                                    <div class="text-sm text-gray-500">
                                        <span class="font-medium">Type:</span> {{ $event->event_type }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <span class="font-medium">Budget:</span> ৳{{ number_format($event->budget, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $event->club->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $event->club->department }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $event->start_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $event->venue->name }}</div>
                                    <div class="text-sm text-gray-500">Capacity: {{ $event->venue->capacity }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div class="flex flex-col space-y-2">
                                        <a href="{{ route('oca.events.show', $event->id) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                        
                                        @if($event->status === 'pending')
                                            <form action="{{ route('oca.events.approve', $event->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-green-600 hover:text-green-900 w-full text-left">
                                                    Approve
                                                </button>
                                            </form>

                                            <form action="{{ route('oca.events.reject', $event->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 w-full text-left">
                                                    Reject
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No pending events found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($events instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-4">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </x-slot>
</x-layouts.oca>
