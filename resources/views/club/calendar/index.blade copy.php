@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">
        <h1 class="text-2xl font-bold text-indigo-900 mb-6">Central Calendar</h1>
        
        <div class="flex">
            <!-- Calendar Section -->
            <div class="flex-1">
                <div class="flex items-center mb-4 space-x-2">
                    <button class="p-2 bg-indigo-600 text-white rounded-lg">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="p-2 bg-indigo-600 text-white rounded-lg">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg">today</button>
                    <h2 class="text-xl font-semibold ml-4">December 2024</h2>
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg ml-auto">month</button>
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
                        @php
                            $daysInMonth = 31;
                            $startDay = 5; // Friday
                            $day = 1;
                        @endphp

                        @for ($i = 0; $i < 42; $i++)
                            @if ($i < $startDay || $day > $daysInMonth)
                                <div class="bg-white p-2 h-24"></div>
                            @else
                                <div class="bg-white p-2 h-24 relative">
                                    <span class="text-sm">{{ $day }}</span>
                                    @if($day === 17)
                                    <div class="mt-1">
                                        <div class="bg-indigo-600 text-white text-xs p-1 rounded">
                                            Winter Fest
                                            <div class="text-xs">BUCC</div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($day === 27)
                                    <div class="mt-1">
                                        <div class="bg-orange-400 text-white text-xs p-1 rounded">Today</div>
                                    </div>
                                    @endif
                                    @php $day++; @endphp
                                </div>
                            @endif
                        @endfor
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
                        <div class="bg-white p-3 rounded-lg shadow-sm border">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-calendar-alt text-indigo-600 mr-2"></i>
                                <h4 class="font-medium">BizBee case competition</h4>
                            </div>
                            <p class="text-sm text-gray-600">Cultural Night: Beats of Bengal</p>
                            <p class="text-xs text-gray-500 mt-1">Dec 31, 2024</p>
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm border">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-calendar-alt text-indigo-600 mr-2"></i>
                                <h4 class="font-medium">Quiz Competition</h4>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Jan 1, 2025</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
@endpush

@push('scripts')
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
@endpush
