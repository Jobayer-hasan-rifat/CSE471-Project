@extends('components.layouts.oca')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="flex h-[600px]">
            <!-- Club List Sidebar -->
            <div class="w-1/4 border-r">
                <div class="p-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-900">Club Chats</h2>
                </div>
                <div class="overflow-y-auto h-[calc(600px-4rem)]">
                    @foreach($clubs as $club)
                        <a href="{{ route('oca.chat', ['club_id' => $club->id]) }}" 
                           class="block p-4 hover:bg-gray-50 {{ $selectedClub && $selectedClub->id === $club->id ? 'bg-blue-50' : '' }}">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('images/' . strtolower($club->name) . '.png') }}" 
                                         alt="{{ $club->name }}" 
                                         class="h-10 w-10 rounded-full">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ strtoupper($club->name) }}
                                    </p>
                                    @php
                                        $lastMessage = $club->chats()->latest()->first();
                                    @endphp
                                    @if($lastMessage)
                                        <p class="text-sm text-gray-500 truncate">
                                            {{ $lastMessage->message }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Chat Area -->
            <div class="flex-1 flex flex-col">
                @if($selectedClub)
                    <!-- Chat Header -->
                    <div class="p-4 border-b flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('images/' . strtolower($selectedClub->name) . '.png') }}" 
                                 alt="{{ $selectedClub->name }}" 
                                 class="h-10 w-10 rounded-full">
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ strtoupper($selectedClub->name) }}</h2>
                            <p class="text-sm text-gray-500">{{ $selectedClub->full_name }}</p>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div id="chat-messages" class="flex-1 overflow-y-auto p-4">
                        @foreach($messages as $message)
                            <div class="mb-4 {{ $message['is_mine'] ? 'text-right' : 'text-left' }}">
                                <div class="inline-block max-w-[70%] {{ $message['is_mine'] ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-900' }} rounded-lg px-4 py-2">
                                    <p class="text-sm">{{ $message['message'] }}</p>
                                    <span class="text-xs {{ $message['is_mine'] ? 'text-blue-100' : 'text-gray-500' }}">
                                        {{ $message['time'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Message Input -->
                    <div class="p-4 border-t">
                        <form id="message-form" class="flex space-x-4">
                            @csrf
                            <input type="hidden" id="club-id" value="{{ $selectedClub->id }}">
                            <input type="text" 
                                   id="message-input"
                                   class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Type your message...">
                            <button type="submit"
                                    class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Send
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex-1 flex items-center justify-center">
                        <p class="text-gray-500">Select a club to start chatting</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const chatMessages = document.getElementById('chat-messages');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const clubId = document.getElementById('club-id')?.value;

    // Scroll to bottom of chat
    function scrollToBottom() {
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }

    // Add new message to chat
    function addMessage(messageData) {
        if (!chatMessages) return;

        const div = document.createElement('div');
        div.className = `mb-4 ${messageData.is_mine ? 'text-right' : 'text-left'}`;
        
        const innerDiv = document.createElement('div');
        innerDiv.className = `inline-block max-w-[70%] ${messageData.is_mine ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-900'} rounded-lg px-4 py-2`;
        
        const p = document.createElement('p');
        p.className = 'text-sm';
        p.textContent = messageData.message;
        
        const span = document.createElement('span');
        span.className = `text-xs ${messageData.is_mine ? 'text-blue-100' : 'text-gray-500'}`;
        span.textContent = messageData.time;
        
        innerDiv.appendChild(p);
        innerDiv.appendChild(span);
        div.appendChild(innerDiv);
        chatMessages.appendChild(div);
        
        scrollToBottom();
    }

    if (messageForm) {
        // Send message
        messageForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;
            
            try {
                const response = await fetch('{{ route("oca.chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ 
                        message,
                        club_id: clubId
                    })
                });
                
                const data = await response.json();
                if (data.success) {
                    addMessage(data.message);
                    messageInput.value = '';
                }
            } catch (error) {
                console.error('Error sending message:', error);
            }
        });
    }

    if (clubId) {
        // Check for new messages every 5 seconds
        setInterval(async () => {
            try {
                const response = await fetch(`{{ route('oca.chat.get-new') }}?club_id=${clubId}`);
                const messages = await response.json();
                
                messages.forEach(message => {
                    addMessage(message);
                });
            } catch (error) {
                console.error('Error checking messages:', error);
            }
        }, 5000);
    }

    // Initial scroll to bottom
    scrollToBottom();
</script>
@endpush
@endsection