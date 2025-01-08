<x-layouts.oca>
    <x-slot:content>
        <div class="container mx-auto px-6 py-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Announcements</h1>
                <a href="{{ route('oca.announcements.create') }}" 
                   class="bg-[#686de0] text-white px-4 py-2 rounded-lg hover:bg-[#4834d4] transition-colors">
                    Create Announcement
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="divide-y divide-gray-200">
                    @forelse($announcements as $announcement)
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $announcement->title }}</h3>
                                    <p class="mt-2 text-gray-600">{{ $announcement->content }}</p>
                                    <div class="mt-2 text-sm text-gray-500">
                                        Posted {{ $announcement->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <form action="{{ route('oca.announcements.destroy', $announcement) }}" method="POST" class="flex-shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Are you sure you want to delete this announcement?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            No announcements found
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4">
                {{ $announcements->links() }}
            </div>
        </div>
    </x-slot:content>
</x-layouts.oca>
