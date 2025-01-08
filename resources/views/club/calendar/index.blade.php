<x-layouts.club>
    <x-slot name="content">
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Event Calendar</h1>
                <a href="{{ route('club.events.create') }}" 
                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    Create New Event
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Calendar Legend -->
                <div class="lg:col-span-1 bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Legend</h3>
                    
                    <!-- Event Status -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-600 mb-2">Event Status</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-[#22c55e] mr-2"></div>
                                <span>Approved</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-[#fbbf24] mr-2"></div>
                                <span>Pending</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-[#ef4444] mr-2"></div>
                                <span>Rejected</span>
                            </div>
                        </div>
                    </div>

                    <!-- Club Colors -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-600 mb-2">Clubs</h4>
                        <div class="space-y-2">
                            @foreach($clubs as $club)
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-2" 
                                     style="background-color: {{ $colors[$club->id % count($colors)] }}"></div>
                                <span>{{ $club->name }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Calendar -->
                <div class="lg:col-span-3 bg-white rounded-lg shadow-lg p-6">
                    <div id="calendar" class="min-h-[700px]"></div>
                </div>
            </div>
        </div>

        <!-- Event Details Modal -->
        <div class="overlay" id="overlay"></div>
        <div class="event-details" id="eventDetails">
            <h3 class="text-xl font-bold mb-4" id="eventTitle"></h3>
            <div class="space-y-3">
                <p><span class="font-semibold">Club:</span> <span id="eventClub"></span></p>
                <p><span class="font-semibold">Description:</span> <span id="eventDescription"></span></p>
                <p><span class="font-semibold">Venue:</span> <span id="eventVenue"></span></p>
                <p><span class="font-semibold">Start:</span> <span id="eventStart"></span></p>
                <p><span class="font-semibold">End:</span> <span id="eventEnd"></span></p>
                <p><span class="font-semibold">Status:</span> 
                    <span id="eventStatus" class="px-2 py-1 rounded-full text-sm"></span>
                </p>
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="closeEventDetails()" 
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Close
                </button>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <style>
        .fc {
            font-family: inherit;
        }
        .fc-toolbar-title {
            font-size: 1.5rem !important;
            font-weight: 600 !important;
        }
        .fc-button {
            background-color: rgb(79 70 229) !important;
            border-color: rgb(79 70 229) !important;
        }
        .fc-button:hover {
            background-color: rgb(67 56 202) !important;
            border-color: rgb(67 56 202) !important;
        }
        .fc-button-active {
            background-color: rgb(67 56 202) !important;
            border-color: rgb(67 56 202) !important;
        }
        .fc-event {
            cursor: pointer;
            padding: 2px 4px;
        }
        .fc-event-title {
            font-weight: 500;
        }
        .fc-daygrid-day {
            height: 100px !important;
        }
        .event-details {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1001;
            width: 90%;
            max-width: 500px;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
    </style>
    @endpush

    @push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'standard',
                navLinks: true,
                editable: false,
                dayMaxEvents: true,
                events: '{{ route('club.calendar.events') }}',
                eventClick: function(info) {
                    document.getElementById('eventTitle').textContent = info.event.title;
                    document.getElementById('eventDescription').textContent = info.event.extendedProps.description || 'No description available';
                    document.getElementById('eventVenue').textContent = info.event.extendedProps.venue || 'No venue specified';
                    document.getElementById('eventClub').textContent = info.event.extendedProps.club || 'Unknown club';
                    document.getElementById('eventStart').textContent = info.event.start ? info.event.start.toLocaleString() : 'Not specified';
                    document.getElementById('eventEnd').textContent = info.event.end ? info.event.end.toLocaleString() : 'Not specified';
                    
                    const statusElement = document.getElementById('eventStatus');
                    statusElement.textContent = info.event.extendedProps.status;
                    
                    // Set status color
                    switch(info.event.extendedProps.status) {
                        case 'approved':
                            statusElement.className = 'px-2 py-1 rounded-full text-sm bg-green-100 text-green-800';
                            break;
                        case 'pending':
                            statusElement.className = 'px-2 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800';
                            break;
                        case 'rejected':
                            statusElement.className = 'px-2 py-1 rounded-full text-sm bg-red-100 text-red-800';
                            break;
                        default:
                            statusElement.className = 'px-2 py-1 rounded-full text-sm bg-gray-100 text-gray-800';
                    }
                    
                    document.getElementById('overlay').style.display = 'block';
                    document.getElementById('eventDetails').style.display = 'block';
                }
            });
            calendar.render();
        });

        function closeEventDetails() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('eventDetails').style.display = 'none';
        }

        document.getElementById('overlay').addEventListener('click', closeEventDetails);
    </script>
    @endpush
</x-layouts.club>
