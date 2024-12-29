@extends('layouts.app')

@section('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">Event Calendar</h1>
        @can('create', App\Models\Event::class)
            <a href="{{ route('events.book') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Book New Event
            </a>
        @endcan
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="venue-filter" class="block text-sm font-medium text-gray-700">Venue</label>
                <select id="venue-filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Venues</option>
                    @foreach($venues as $venue)
                        <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="club-filter" class="block text-sm font-medium text-gray-700">Club</label>
                <select id="club-filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Clubs</option>
                    @foreach($clubs as $club)
                        <option value="{{ $club->id }}">{{ $club->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="view-type" class="block text-sm font-medium text-gray-700">View</label>
                <select id="view-type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="dayGridMonth">Month</option>
                    <option value="timeGridWeek">Week</option>
                    <option value="timeGridDay">Day</option>
                    <option value="listWeek">List</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Calendar -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div id="calendar"></div>
    </div>

    <!-- Event Details Modal -->
    <div id="event-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 id="event-title" class="text-lg font-medium leading-6 text-gray-900 mb-2"></h3>
                <div class="space-y-2">
                    <p id="event-club" class="text-sm text-gray-600"></p>
                    <p id="event-venue" class="text-sm text-gray-600"></p>
                    <p id="event-time" class="text-sm text-gray-600"></p>
                </div>
                <div class="mt-4 flex justify-end">
                    <button onclick="document.getElementById('event-modal').classList.add('hidden')"
                        class="bg-gray-200 px-4 py-2 rounded text-gray-600 hover:bg-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        events: {
            url: '{{ route("api.events.calendar") }}',
            method: 'GET',
            extraParams: function() {
                return {
                    venue_id: document.getElementById('venue-filter').value,
                    club_id: document.getElementById('club-filter').value
                };
            }
        },
        eventClick: function(info) {
            const event = info.event;
            document.getElementById('event-title').textContent = event.title;
            document.getElementById('event-club').textContent = `Club: ${event.extendedProps.club}`;
            document.getElementById('event-venue').textContent = `Venue: ${event.extendedProps.venue}`;
            document.getElementById('event-time').textContent = `Time: ${event.start.toLocaleString()} - ${event.end.toLocaleString()}`;
            document.getElementById('event-modal').classList.remove('hidden');
        },
        eventDidMount: function(info) {
            info.el.title = `${info.event.title}\nClub: ${info.event.extendedProps.club}\nVenue: ${info.event.extendedProps.venue}`;
        }
    });
    calendar.render();

    // Handle filter changes
    document.getElementById('venue-filter').addEventListener('change', function() {
        calendar.refetchEvents();
    });
    document.getElementById('club-filter').addEventListener('change', function() {
        calendar.refetchEvents();
    });
    document.getElementById('view-type').addEventListener('change', function() {
        calendar.changeView(this.value);
    });
});
</script>
@endsection
