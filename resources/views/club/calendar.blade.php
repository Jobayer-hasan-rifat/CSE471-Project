@extends('components.layouts.club')

@section('content')
<div class="bg-white min-h-screen">
    <div class="p-6">
        <!-- Calendar Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-[#2f3542]">Central Calendar</h1>
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/' . strtolower(auth()->user()->club->name) . '.png') }}" 
                     alt="Club Logo" 
                     class="w-8 h-8 rounded-full">
            </div>
        </div>

        <!-- Calendar Navigation -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-2">
                <button class="p-2 rounded-lg bg-[#686de0] text-white hover:bg-[#585bc0]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button class="p-2 rounded-lg bg-[#686de0] text-white hover:bg-[#585bc0]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <button class="px-4 py-2 rounded-lg bg-[#686de0] text-white hover:bg-[#585bc0]">
                    today
                </button>
            </div>
            <h2 class="text-xl font-semibold text-[#2f3542]">{{ now()->format('F Y') }}</h2>
            <button class="px-4 py-2 rounded-lg bg-[#686de0] text-white hover:bg-[#585bc0]">
                month
            </button>
        </div>

        <!-- Calendar Grid -->
        <div class="border rounded-lg bg-white">
            <!-- Days of Week -->
            <div class="grid grid-cols-7 gap-px bg-gray-200 text-sm font-medium text-gray-600">
                <div class="px-4 py-3 text-center">Sun</div>
                <div class="px-4 py-3 text-center">Mon</div>
                <div class="px-4 py-3 text-center">Tue</div>
                <div class="px-4 py-3 text-center">Wed</div>
                <div class="px-4 py-3 text-center">Thu</div>
                <div class="px-4 py-3 text-center">Fri</div>
                <div class="px-4 py-3 text-center">Sat</div>
            </div>

            <!-- Calendar Days -->
            <div class="grid grid-cols-7 gap-px bg-gray-200">
                @php
                    $date = now()->startOfMonth();
                    $daysInMonth = now()->daysInMonth;
                    $firstDayOfWeek = $date->dayOfWeek;
                    
                    // Previous month's overflow days
                    $previousMonth = now()->subMonth();
                    $daysInPreviousMonth = $previousMonth->daysInMonth;
                    $previousMonthStart = $daysInPreviousMonth - $firstDayOfWeek + 1;
                @endphp

                <!-- Previous month overflow days -->
                @for ($i = 0; $i < $firstDayOfWeek; $i++)
                    <div class="bg-white p-2 h-32">
                        <span class="text-gray-400">{{ $previousMonthStart + $i }}</span>
                    </div>
                @endfor

                <!-- Current month days -->
                @for ($day = 1; $day <= $daysInMonth; $day++)
                    <div class="bg-white p-2 h-32 relative {{ $day == now()->day ? 'bg-red-50' : '' }}">
                        <span class="text-gray-700 {{ $day == now()->day ? 'text-red-500 font-bold' : '' }}">{{ $day }}</span>
                        
                        <!-- Events for this day -->
                        @foreach($events as $event)
                            @if(Carbon\Carbon::parse($event['start'])->day == $day)
                                <div class="mt-1">
                                    <div class="text-xs p-1 rounded-md {{ $event['isMyClub'] ? 'bg-[#686de0] text-white' : 'bg-[#4CAF50] text-white' }}">
                                        <div class="truncate">{{ $event['title'] }}</div>
                                        <div class="text-xs opacity-75">{{ $event['club_name'] }}</div>
                                        <div class="text-xs">{{ Carbon\Carbon::parse($event['start'])->format('h:i A') }}</div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endfor

                <!-- Next month overflow days -->
                @php
                    $remainingDays = 42 - ($firstDayOfWeek + $daysInMonth); // 42 is 6 rows × 7 days
                @endphp
                @for ($i = 1; $i <= $remainingDays; $i++)
                    <div class="bg-white p-2 h-32">
                        <span class="text-gray-400">{{ $i }}</span>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Event Indicators -->
    <div class="fixed top-24 right-6 bg-white p-6 rounded-lg shadow-lg w-80">
        <h3 class="text-lg font-semibold mb-4">Event Indicators</h3>
        <div class="space-y-3">
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full bg-[#686de0] mr-3"></div>
                <span class="text-sm text-gray-600">Scheduled Events</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full bg-red-500 mr-3"></div>
                <span class="text-sm text-gray-600">Today</span>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-4">Upcoming Events</h3>
            <div class="space-y-4">
                @foreach($events->where('start', '>=', now())->take(5) as $event)
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-[#686de0] flex items-center justify-center text-white text-xs">
                            {{ strtoupper(substr($event['club_name'], 0, 2)) }}
                        </div>
                        <div>
                            <div class="text-sm font-medium">{{ $event['title'] }}</div>
                            <div class="text-xs text-gray-500">{{ Carbon\Carbon::parse($event['start'])->format('M d, Y') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
