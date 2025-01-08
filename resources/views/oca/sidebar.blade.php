<div class="w-64 min-h-screen bg-[#4834d4] text-white">
    <!-- OCA Logo/Title -->
    <div class="p-6 flex flex-col items-center">
        <div class="shrink-0 flex items-center">
            <img src="{{ asset('images/oca.png') }}" alt="OCA Logo" class="h-8 w-auto">
            <span class="ml-2 text-lg font-bold">OCA</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 flex flex-col h-[calc(100vh-120px)]">
        <div class="flex-1 space-y-1">
            <a href="{{ route('welcome') }}" 
               class="flex items-center px-6 py-3 text-white hover:bg-[#686de0]">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Home
            </a>

            <a href="{{ route('oca.dashboard') }}" 
               class="flex items-center px-6 py-3 text-white {{ request()->routeIs('oca.dashboard') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                Dashboard
            </a>

            <!-- Divider -->
            <div class="my-4 border-t border-[#686de0]"></div>
            
            <a href="{{ route('oca.events.pending') }}" 
               class="flex items-center px-6 py-3 text-white {{ request()->routeIs('oca.events.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}"
               onclick="loadContent(this, '{{ route('oca.events.pending') }}'); return false;">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Pending Events
            </a>
            
            <a href="{{ route('oca.calendar.index') }}" 
               class="flex items-center px-6 py-3 text-white {{ request()->routeIs('oca.calendar.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}"
               onclick="loadContent(this, '{{ route('oca.calendar.index') }}'); return false;">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Calendar
            </a>
            
            <a href="{{ route('oca.clubs.index') }}" 
               class="flex items-center px-6 py-3 text-white {{ request()->routeIs('oca.clubs.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}"
               onclick="loadContent(this, '{{ route('oca.clubs.index') }}'); return false;">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Clubs
            </a>

            <a href="{{ route('oca.venues.index') }}" 
               class="flex items-center px-6 py-3 text-white {{ request()->routeIs('oca.venues.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}"
               onclick="loadContent(this, '{{ route('oca.venues.index') }}'); return false;">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Venues
            </a>

            <a href="{{ route('oca.chat.index') }}" 
               class="flex items-center px-6 py-3 text-white {{ request()->routeIs('oca.chat.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}"
               onclick="loadContent(this, '{{ route('oca.chat.index') }}'); return false;">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                Chat
            </a>

            <a href="{{ route('oca.announcements.index') }}" 
               class="flex items-center px-6 py-3 text-white {{ request()->routeIs('oca.announcements.*') ? 'bg-[#686de0]' : 'hover:bg-[#686de0]' }}"
               onclick="loadContent(this, '{{ route('oca.announcements.index') }}'); return false;">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
                Announcements
            </a>
        </div>

        <!-- Logout Section -->
        <div class="mt-auto border-t border-[#686de0] pt-4 mb-8">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center px-6 py-3 text-white hover:bg-[#686de0] transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </nav>
</div>

@push('scripts')
<script>
function loadContent(element, url) {
    // Remove active class from all links
    document.querySelectorAll('nav a').forEach(a => {
        a.classList.remove('bg-[#686de0]');
        a.classList.add('hover:bg-[#686de0]');
    });

    // Add active class to clicked link
    element.classList.add('bg-[#686de0]');
    element.classList.remove('hover:bg-[#686de0]');

    // Load content via AJAX
    const contentDiv = document.getElementById('content');
    
    // Add loading state
    contentDiv.style.opacity = '0';
    contentDiv.innerHTML = `
        <div class="flex justify-center items-center py-12">
            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    `;

    // Fetch content
    fetch(url)
        .then(response => response.text())
        .then(html => {
            setTimeout(() => {
                contentDiv.innerHTML = html;
                contentDiv.style.opacity = '1';
            }, 200);
        })
        .catch(error => {
            contentDiv.innerHTML = `
                <div class="text-center text-red-600">
                    Error loading content. Please try again.
                </div>
            `;
        });
}
</script>
@endpush
