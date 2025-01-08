<x-layouts.oca>
    <x-slot:content>
        <div class="container mx-auto px-6 py-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold">Create Announcement</h1>
                <a href="{{ route('oca.announcements.index') }}" 
                   class="text-gray-600 hover:text-gray-900">
                    Back to Announcements
                </a>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <form action="{{ route('oca.announcements.store') }}" method="POST" class="p-6">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               value="{{ old('title') }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                        <textarea name="content" id="content" rows="5" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                  required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-[#686de0] text-white px-4 py-2 rounded-lg hover:bg-[#4834d4] transition-colors">
                            Create Announcement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-slot:content>
</x-layouts.oca>
