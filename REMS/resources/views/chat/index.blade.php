@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="flex h-[calc(100vh-2rem)]">
        <!-- Left sidebar - Contact list -->
        <div class="w-1/4 border-r">
            <div class="p-4">
                <h2 class="text-xl font-bold mb-4">Messages</h2>
                <div class="relative mb-4">
                    <input type="text" placeholder="Search" class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>

                <!-- Contact List -->
                <div class="space-y-2">
                    @foreach($clubs as $club)
                    <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 cursor-pointer contact-item" 
                         data-club-id="{{ $club->id }}">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            @if($club->logo)
                                <img src="{{ $club->logo }}" alt="{{ $club->name }}" class="w-full h-full object-cover rounded-full">
                            @else
                                <span class="text-lg font-semibold text-indigo-600">{{ $club->initial }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium">{{ $club->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $club->email }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right side - Chat area -->
        <div class="flex-1 flex flex-col">
            <!-- Chat header -->
            <div class="p-4 border-b flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                        <span class="text-indigo-600 font-semibold" id="chat-contact-initial"></span>
                    </div>
                    <div>
                        <h3 class="font-medium" id="chat-contact-name"></h3>
                        <p class="text-sm text-gray-500" id="chat-contact-email"></p>
                    </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-video"></i>
                </button>
            </div>

            <!-- Chat messages -->
            <div class="flex-1 overflow-y-auto p-4" id="chat-messages">
                <div class="space-y-4">
                    <!-- Messages will be loaded here -->
                </div>
            </div>

            <!-- Chat input -->
            <div class="p-4 border-t">
                <form id="message-form" class="flex items-center space-x-4">
                    <div class="flex-1 relative">
                        <input type="text" 
                               id="message-input"
                               placeholder="Write your message..." 
                               class="w-full pl-4 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <button type="button" class="absolute right-3 top-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-paperclip"></i>
                        </button>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Send <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let currentClubId = null;
    
    // Handle club selection
    $('.contact-item').click(function() {
        const clubId = $(this).data('club-id');
        currentClubId = clubId;
        
        // Update UI
        $('.contact-item').removeClass('bg-gray-100');
        $(this).addClass('bg-gray-100');
        
        // Load messages
        loadMessages(clubId);
    });
    
    // Handle message sending
    $('#message-form').submit(function(e) {
        e.preventDefault();
        if (!currentClubId) return;
        
        const message = $('#message-input').val();
        if (!message.trim()) return;
        
        $.post('/chat/send', {
            club_id: currentClubId,
            message: message
        }, function(response) {
            if (response.success) {
                $('#message-input').val('');
                appendMessage(response.message);
            }
        });
    });
    
    function loadMessages(clubId) {
        $.get(`/chat/messages/${clubId}`, function(messages) {
            $('#chat-messages').empty();
            messages.forEach(appendMessage);
            scrollToBottom();
        });
    }
    
    function appendMessage(message) {
        const align = message.is_sender ? 'justify-end' : 'justify-start';
        const bgColor = message.is_sender ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-900';
        
        const html = `
            <div class="flex ${align} mb-4">
                <div class="max-w-xs ${bgColor} rounded-lg p-3">
                    <p class="text-sm">${message.content}</p>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-xs opacity-75">${message.user_name}</span>
                        <span class="text-xs opacity-75">${formatTime(message.created_at)}</span>
                    </div>
                </div>
            </div>
        `;
        
        $('#chat-messages').append(html);
        scrollToBottom();
    }
    
    function scrollToBottom() {
        const chatMessages = $('#chat-messages');
        chatMessages.scrollTop(chatMessages[0].scrollHeight);
    }
    
    function formatTime(timestamp) {
        return new Date(timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }
});
</script>
@endpush
@endsection
