<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <img src="{{ asset('images/brac.png') }}" alt="BRAC Logo" class="h-8 w-auto">
                <h2 class="ml-4 text-xl font-semibold text-gray-800">OCA Dashboard</h2>
            </div>
            <!-- User Dropdown -->
            <div class="flex items-center">
                <div class="relative">
                    <button type="button" class="flex items-center gap-2 bg-white p-2 rounded-lg">
                        <span class="text-gray-700">{{ Auth::user()->name }}</span>
                        <img src="{{ asset('images/oca.png') }}" alt="Profile" class="h-8 w-8 rounded-full">
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>
