<x-layouts.oca>
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- Club Header -->
            <div class="flex items-center mb-8">
                <img src="{{ asset($club->logo ?? 'images/default-club-logo.png') }}" 
                     alt="{{ $club->name }} Logo" 
                     class="w-24 h-24 rounded-full object-cover mr-6">
                <div>
                    <h1 class="text-3xl font-bold text-indigo-900">{{ $club->name }}</h1>
                    <p class="text-gray-600">{{ $club->email }}</p>
                </div>
            </div>

            <!-- Club Description -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">About</h2>
                <p class="text-gray-700">{{ $club->description ?? 'No description available.' }}</p>
            </div>

            <!-- Club Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold text-center text-indigo-900">Total Events</h3>
                    <p class="text-3xl text-center">{{ $stats['total_events'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold text-center text-green-600">Approved Events</h3>
                    <p class="text-3xl text-center">{{ $stats['approved_events'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold text-center text-yellow-600">Pending Events</h3>
                    <p class="text-3xl text-center">{{ $stats['pending_events'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold text-center text-red-600">Rejected Events</h3>
                    <p class="text-3xl text-center">{{ $stats['rejected_events'] }}</p>
                </div>
            </div>

            <!-- Club Positions -->
            @if($positions && $positions->count() > 0)
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Club Positions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($positions as $position)
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center">
                            @if($position->image)
                            <img src="{{ asset('storage/' . $position->image) }}" 
                                 alt="{{ $position->member_name }}" 
                                 class="w-20 h-20 rounded-full object-cover mr-4">
                            @endif
                            <div>
                                <h3 class="text-lg font-semibold">{{ $position->position_name }}</h3>
                                <p class="text-gray-700">{{ $position->member_name }}</p>
                                @if($position->email)
                                <p class="text-gray-600 text-sm">{{ $position->email }}</p>
                                @endif
                                @if($position->phone)
                                <p class="text-gray-600 text-sm">{{ $position->phone }}</p>
                                @endif
                            </div>
                        </div>
                        @if($position->description)
                        <p class="text-gray-600 mt-2 text-sm">{{ $position->description }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Contact Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Contact Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600"><strong>Email:</strong> {{ $club->email }}</p>
                        @if($club->phone)
                        <p class="text-gray-600"><strong>Phone:</strong> {{ $club->phone }}</p>
                        @endif
                    </div>
                    <div>
                        @if($club->facebook)
                        <p class="text-gray-600">
                            <strong>Facebook:</strong> 
                            <a href="{{ $club->facebook }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">
                                Visit Page
                            </a>
                        </p>
                        @endif
                        @if($club->instagram)
                        <p class="text-gray-600">
                            <strong>Instagram:</strong> 
                            <a href="{{ $club->instagram }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">
                                Visit Profile
                            </a>
                        </p>
                        @endif
                        @if($club->linkedin)
                        <p class="text-gray-600">
                            <strong>LinkedIn:</strong> 
                            <a href="{{ $club->linkedin }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">
                                Visit Profile
                            </a>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.oca>