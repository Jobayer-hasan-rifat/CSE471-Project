<x-layouts.oca>
    <x-slot:content>
        <div class="container mx-auto px-6 py-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-2xl font-semibold mb-6">Club Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($clubs as $club)
                        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold">{{ $club->name }}</h3>
                                <span class="px-2 py-1 text-xs rounded-full {{ $club->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $club->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">President:</span> {{ $club->president_name }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Email:</span> {{ $club->email }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Phone:</span> {{ $club->phone }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Events Count:</span> {{ $club->events_count }}
                                </p>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="{{ route('oca.clubs.show', $club) }}" 
                                   class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center text-gray-500 py-8">
                            No clubs found.
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $clubs->links() }}
                </div>
            </div>
        </div>
    </x-slot:content>
</x-layouts.oca>
