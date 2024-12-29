<!-- resources/views/calendar/index.blade.php -->
<x-app-layout>
    <div class="p-8">
        <div class="flex justify-between items-center mb-6">
            <div class="flex space-x-2">
                <button class="bg-[#5D3FD3] text-white px-3 py-1 rounded"><</button>
                <button class="bg-[#5D3FD3] text-white px-3 py-1 rounded">></button>
                <button class="bg-[#5D3FD3] text-white px-4 py-1 rounded">today</button>
            </div>
            <h2 class="text-2xl font-bold">December 2024</h2>
            <button class="bg-[#5D3FD3] text-white px-4 py-1 rounded">month</button>
        </div>
        <div class="mb-6">
            <a href="{{ route('oca.calendar.index') }}" class="text-indigo-600 hover:text-indigo-900">
                <i class="fas fa-arrow-left mr-2"></i>Back to OCA Calendar
            </a>
        </div>

        <div class="grid grid-cols-7 gap-4">
            <!-- Calendar header -->
            <div class="text-center py-2 text-gray-600">Sun</div>
            <!-- Repeat for other days -->

            <!-- Calendar days -->
            @for ($i = 1; $i <= 31; $i++)
                <div class="border p-2 min-h-[100px]">
                    <span class="text-gray-600">{{ $i }}</span>
                    <!-- Event items -->
                </div>
            @endfor
        </div>
    </div>
</x-app-layout>