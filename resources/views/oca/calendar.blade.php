<x-layouts.oca>
    <x-slot:content>
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-indigo-900 mb-6">Central Calendar</h1>
                
                <div class="flex">
                    <!-- Calendar Section -->
                    <div class="flex-1">
                        <div class="flex items-center mb-4 space-x-2">
                            <a href="{{ route('oca.calendar.index', ['month' => $prevMonth]) }}" class="p-2 bg-indigo-600 text-white rounded-lg">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <a href="{{ route('oca.calendar.index', ['month' => $nextMonth]) }}" class="p-2 bg-indigo-600 text-white rounded-lg">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                            <a href="{{ route('oca.calendar.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">today</a>
                            <h2 class="text-xl font-semibold ml-4">{{ $currentMonth }}</h2>
                        </div>

                        <div class="border rounded-lg">
                            <!-- Calendar Header -->
                            <div class="grid grid-cols-7 gap-px bg-gray-200">
                                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                <div class="p-2 text-center text-sm font-medium">{{ $day }}</div>
                                @endforeach
                            </div>

                            <!-- Calendar Grid -->
                            <div class="grid grid-cols-7 gap-px bg-gray-200">
                                @foreach($calendar as $date => $events)
                                    <div class="bg-white p-2 h-24 relative">
                                        <span class="text-sm {{ $date === $today ? 'bg-orange-400 text-white px-2 py-1 rounded-full' : '' }}">
                                            {{ \Carbon\Carbon::parse($date)->format('j') }}
                                        </span>
                                        @foreach($events as $event)
                                            <div class="mt-1">
                                                <div class="bg-indigo-600 text-white text-xs p-1 rounded">
                                                    {{ $event->name }}
                                                    <div class="text-xs">{{ $event->club->name }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar -->
                    <div class="w-64 ml-6">
                        <!-- Event Indicators -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-3">Event Indicators</h3>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-indigo-600 mr-2"></div>
                                    <span>Scheduled Events</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-orange-400 mr-2"></div>
                                    <span>Today</span>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Events -->
                        <div>
                            <h3 class="text-lg font-semibold mb-3">Upcoming Events</h3>
                            <div class="space-y-3">
                                @foreach($upcomingEvents as $event)
                                    <div class="bg-white p-3 rounded-lg shadow-sm border">
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-calendar-alt text-indigo-600 mr-2"></i>
                                            <h4 class="font-medium">{{ $event->name }}</h4>
                                        </div>
                                        <p class="text-sm text-gray-600">{{ $event->club->name }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $event->start_date->format('M d, Y') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:content>

    @push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: @json($events),
                eventClick: function(info) {
                    // Handle event click
                    alert('Event: ' + info.event.title);
                }
            });
            calendar.render();
        });
    </script>
    @endpush
</x-layouts.oca>
