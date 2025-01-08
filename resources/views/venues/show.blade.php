@extends('components.layouts.admin')

@section('title', $venue->name)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ $venue->name }}</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.venues.edit', $venue) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit Venue
            </a>
            <a href="{{ route('admin.venues.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Back to Venues
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Venue Details -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Venue Information</h2>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Capacity</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $venue->capacity }} people</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $venue->location }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $venue->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $venue->is_available ? 'Available' : 'Not Available' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Description</h2>
                    <p class="text-gray-700">{{ $venue->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Upcoming Events</h2>
        </div>
        <div class="p-6">
            @if($events->count() > 0)
                <div class="space-y-4">
                    @foreach($events as $event)
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $event->title }}</h3>
                                <p class="text-sm text-gray-500">{{ $event->start_date->format('M d, Y h:i A') }} - {{ $event->end_date->format('M d, Y h:i A') }}</p>
                                <p class="text-sm text-gray-500">Organized by: {{ $event->club->name }}</p>
                            </div>
                            <span class="px-2 py-1 text-sm rounded-full {{ $event->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $events->links() }}
                </div>
            @else
                <p class="text-gray-500">No upcoming events scheduled for this venue.</p>
            @endif
        </div>
    </div>
</div>
@endsection
