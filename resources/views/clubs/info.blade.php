<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6">Club Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($clubs as $club)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">{{ $club->name }}</h3>
                            <span class="px-2 py-1 text-xs rounded-full {{ $club->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $club->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <div class="text-sm text-gray-600 mb-4">
                            <p><strong>Email:</strong> {{ $club->email }}</p>
                            <p class="mt-2"><strong>Description:</strong></p>
                            <p>{{ $club->description }}</p>
                        </div>

                        <div class="mt-4">
                            <h4 class="font-semibold mb-2">Recent Events</h4>
                            <div class="space-y-2">
                                @forelse($club->events->take(3) as $event)
                                <div class="flex justify-between items-center text-sm">
                                    <span>{{ $event->title }}</span>
                                    <span class="px-2 py-1 rounded-full text-xs
                                        @if($event->status === 'approved') bg-green-100 text-green-800
                                        @elseif($event->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>
                                @empty
                                <p class="text-sm text-gray-500">No recent events</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900 text-sm">View Details →</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
