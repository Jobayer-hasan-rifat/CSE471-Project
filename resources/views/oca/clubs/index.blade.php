<x-layouts.oca>
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-2xl font-bold text-indigo-900 mb-6">Clubs</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($clubs as $club)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @php
                                    $logoPath = 'images/';
                                    switch(strtolower($club->name)) {
                                        case 'bucc':
                                            $logoPath .= 'bucc.png';
                                            break;
                                        case 'buac':
                                            $logoPath .= 'buac.png';
                                            break;
                                        case 'robu':
                                            $logoPath .= 'robu.png';
                                            break;
                                        case 'bizbee':
                                            $logoPath .= 'bizbee.png';
                                            break;
                                        default:
                                            $logoPath .= 'default-club-logo.png';
                                    }
                                @endphp
                                <img src="{{ asset($logoPath) }}" 
                                     alt="{{ $club->name }} Logo" 
                                     class="w-16 h-16 rounded-full object-cover">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $club->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $club->email }}</p>
                                <p class="text-sm text-gray-500 mt-1">Members: {{ $club->members_count ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="mt-4 space-y-2">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Events this month:</span> 
                                {{ $club->events_count ?? 0 }}
                            </div>
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Total Budget:</span> 
                                ৳{{ number_format($club->total_budget ?? 0) }}
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <a href="{{ route('oca.clubs.show', $club) }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                View Details
                                <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.oca>
