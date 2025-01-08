<x-layouts.club>
    <x-slot name="content">
        <div class="container mx-auto px-4 py-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold">Announcements</h2>
                </div>

                <!-- Announcements List -->
                <div class="space-y-6">
                    @forelse($announcements as $announcement)
                        <div class="border rounded-lg p-4">
                            <div class="mb-2">
                                <h3 class="text-lg font-semibold">{{ $announcement->title }}</h3>
                                <p class="text-sm text-gray-500">
                                    Posted by {{ $announcement->user->name }} on {{ $announcement->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="prose max-w-none">
                                {!! nl2br(e($announcement->content)) !!}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500">No announcements yet.</p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $announcements->links() }}
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-layouts.club>
