@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="mb-6">
            <a href="{{ route('club.calendar.index') }}" class="text-indigo-600 hover:text-indigo-900">
                <i class="fas fa-arrow-left mr-2"></i>Back to Calendar
            </a>
        </div>

        <div class="mb-6">
            <h2 class="text-2xl font-bold mb-2">{{ $event->name }}</h2>
            <div class="text-gray-600">
                <p><i class="fas fa-users mr-2"></i>Organized by {{ $event->club->name }}</p>
                <p><i class="fas fa-map-marker-alt mr-2"></i>{{ $event->venue->name }}</p>
                <p><i class="fas fa-calendar mr-2"></i>{{ $event->start_date->format('F j, Y g:i A') }} - {{ $event->end_date->format('F j, Y g:i A') }}</p>
            </div>
        </div>

        <div class="prose max-w-none">
            <h3 class="text-lg font-semibold mb-2">Description</h3>
            <p>{{ $event->description }}</p>
        </div>

        @if($event->budget)
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Budget</h3>
            <p class="text-gray-600">৳{{ number_format($event->budget) }}</p>
        </div>
        @endif

        @if($event->expected_attendance)
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Expected Attendance</h3>
            <p class="text-gray-600">{{ number_format($event->expected_attendance) }} people</p>
        </div>
        @endif
    </div>
</div>
@endsection
