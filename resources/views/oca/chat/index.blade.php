<x-layouts.oca>
    <x-slot:content>
        <div class="flex h-[calc(100vh-4rem)] bg-gray-100">
            <!-- Sidebar -->
            <div class="w-80 bg-white border-r flex flex-col">
                <div class="p-4 border-b">
                    <h1 class="text-xl font-semibold text-gray-800">Messages</h1>
                    <div class="mt-2">
                        <input type="text" placeholder="Search" class="w-full px-3 py-2 bg-gray-100 rounded-md text-sm focus:outline-none">
                    </div>
                </div>
                
                <!-- Club/User List -->
                <div class="flex-1 overflow-y-auto">
                    @foreach($clubs as $club)
                        <div class="club-card hover:bg-gray-50 cursor-pointer p-3 border-b" data-club-id="{{ $club->id }}" data-club-logo="{{ $club->logo_url }}">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <img src="{{ $club->logo_url }}" alt="{{ $club->name }}" class="w-12 h-12 rounded-full object-cover bg-white">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $club->name }}</h3>
                                        <span class="text-xs text-gray-500">time</span>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate">{{ $club->email }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Main Chat Area -->
            <div class="flex-1 flex flex-col">
                <!-- Chat Header -->
                <div class="h-16 border-b bg-white flex items-center justify-between px-4">
                    <div class="flex items-center space-x-3">
                        <div id="selectedClubLogo" class="w-10 h-10 rounded-full bg-white flex items-center justify-center">
                            <img src="" alt="" class="w-full h-full rounded-full object-cover">
                        </div>
                        <div>
                            <h2 id="selectedClubName" class="text-sm font-medium text-gray-900">Select a club to start chatting</h2>
                            <p id="selectedClubEmail" class="text-xs text-gray-500"></p>
                        </div>
                    </div>
                    <button class="p-2 hover:bg-gray-100 rounded-full">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Messages Area -->
                <div id="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-4">
                    <!-- Messages will be dynamically inserted here -->
                </div>

                <!-- Message Input Area -->
                <div class="border-t bg-white p-4">
                    <!-- File Preview -->
                    <div id="filePreview" class="hidden mb-2 p-2 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span id="fileName" class="text-sm text-gray-600 truncate"></span>
                            <button id="removeFile" class="ml-2 text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <!-- Attachment Button -->
                        <button id="attachmentBtn" class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none" title="Attach file">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                        </button>

                        <!-- Hidden File Input -->
                        <input type="file" 
                               id="fileInput" 
                               class="hidden" 
                               accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">

                        <!-- Message Input -->
                        <div class="flex-1 relative">
                            <!-- Emoji Picker -->
                            <div id="emojiPicker" class="hidden absolute bottom-full right-0 mb-2 bg-white border rounded-lg shadow-lg p-2 w-64">
                                <div class="grid grid-cols-8 gap-1 max-h-40 overflow-y-auto">
                                    <!-- Common emojis -->
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">😊</button>
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">😂</button>
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">❤️</button>
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">👍</button>
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">🎉</button>
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">🔥</button>
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">🤔</button>
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">😎</button>
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">🥳</button>
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">😍</button>
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">🙌</button>
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">✨</button>
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">💯</button>
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">👏</button>
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">🎈</button>
                                    <button class="p-1 hover:bg-gray-100 rounded text-xl">🌟</button>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 w-full px-4 py-2 border rounded-full focus-within:border-blue-500">
                                <button id="emojiBtn" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                                <input type="text" 
                                       id="messageInput" 
                                       placeholder="Type a message" 
                                       class="flex-1 focus:outline-none">
                            </div>
                        </div>

                        <!-- Send Button -->
                        <button id="sendMessageBtn" 
                                class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                            Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:content>

    @push('styles')
    <style>
    .message {
        @apply mb-4 flex;
    }
    .message.sent {
        @apply justify-end;
    }
    .message.received {
        @apply justify-start;
    }
    .message-content {
        @apply max-w-[70%] rounded-lg px-4 py-2 space-y-2;
    }
    .message.sent .message-content {
        @apply bg-blue-500 text-white rounded-br-none;
    }
    .message.received .message-content {
        @apply bg-gray-100 text-gray-800 rounded-bl-none;
    }
    .message-time {
        @apply text-xs text-gray-500 mt-1;
    }
    .message-attachment {
        @apply flex items-center p-2 rounded bg-white/10 space-x-2;
    }
    .message-attachment-icon {
        @apply w-8 h-8 flex items-center justify-center rounded bg-white/20 text-lg;
    }
    .message-attachment-info {
        @apply flex-1 min-w-0;
    }
    .message-attachment-actions {
        @apply flex items-center space-x-2;
    }
    </style>
    @endpush

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const clubCards = document.querySelectorAll('.club-card');
        const selectedClubLogo = document.getElementById('selectedClubLogo').querySelector('img');
        const selectedClubName = document.getElementById('selectedClubName');
        const selectedClubEmail = document.getElementById('selectedClubEmail');
        let selectedClubId = null;

        // Message Elements
        const messagesContainer = document.getElementById('messagesContainer');
        const messageInput = document.getElementById('messageInput');
        const sendMessageBtn = document.getElementById('sendMessageBtn');

        // File Upload Elements
        const attachmentBtn = document.getElementById('attachmentBtn');
        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const removeFile = document.getElementById('removeFile');

        // Emoji picker functionality
        const emojiBtn = document.getElementById('emojiBtn');
        const emojiPicker = document.getElementById('emojiPicker');
        
        emojiBtn.addEventListener('click', () => {
            emojiPicker.classList.toggle('hidden');
        });

        // Add emoji to message input when clicked
        emojiPicker.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', () => {
                messageInput.value += button.textContent;
                emojiPicker.classList.add('hidden');
                messageInput.focus();
                sendMessageBtn.disabled = false;
            });
        });

        // Close emoji picker when clicking outside
        document.addEventListener('click', (e) => {
            if (!emojiPicker.contains(e.target) && !emojiBtn.contains(e.target)) {
                emojiPicker.classList.add('hidden');
            }
        });

        // Enable/disable send button based on input
        messageInput.addEventListener('input', function() {
            sendMessageBtn.disabled = !this.value.trim() && !fileInput.files.length;
        });

        // Handle club selection
        clubCards.forEach(card => {
            card.addEventListener('click', function() {
                // Remove active state from all cards
                clubCards.forEach(c => c.classList.remove('bg-gray-100'));
                
                // Add active state to selected card
                this.classList.add('bg-gray-100');
                
                // Update selected club info
                selectedClubId = this.dataset.clubId;
                selectedClubLogo.src = this.dataset.clubLogo;
                selectedClubName.textContent = this.querySelector('h3').textContent;
                selectedClubEmail.textContent = this.querySelector('p').textContent;
                
                // Load messages for selected club
                loadMessages(selectedClubId);
            });
        });

        // Handle file attachment
        attachmentBtn.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function() {
            if (this.files.length) {
                fileName.textContent = this.files[0].name;
                filePreview.classList.remove('hidden');
                sendMessageBtn.disabled = false;
            }
        });

        removeFile.addEventListener('click', function() {
            fileInput.value = '';
            filePreview.classList.add('hidden');
            sendMessageBtn.disabled = !messageInput.value.trim();
        });

        // Handle message sending
        sendMessageBtn.addEventListener('click', function() {
            if (!selectedClubId) return;

            const message = messageInput.value.trim();
            const file = fileInput.files[0];

            // Here you would typically send the message and/or file to your server
            // For now, we'll just add it to the UI
            if (message || file) {
                addMessageToUI({
                    content: message,
                    file: file,
                    sent: true,
                    timestamp: new Date()
                });

                // Clear input
                messageInput.value = '';
                fileInput.value = '';
                filePreview.classList.add('hidden');
                sendMessageBtn.disabled = true;
            }
        });

        function loadMessages(clubId) {
            // Clear existing messages
            messagesContainer.innerHTML = '';
            
            // Here you would typically load messages from your server
            // For now, we'll just add some dummy messages
            addMessageToUI({
                content: 'Hello! How can I help you today?',
                sent: false,
                timestamp: new Date(Date.now() - 1000 * 60 * 5) // 5 minutes ago
            });
        }

        function getFileIcon(fileName) {
            const ext = fileName.split('.').pop().toLowerCase();
            const icons = {
                'pdf': '📄',
                'doc': '📝',
                'docx': '📝',
                'jpg': '🖼️',
                'jpeg': '🖼️',
                'png': '🖼️',
                'gif': '🖼️',
                default: '📎'
            };
            return icons[ext] || icons.default;
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        function addMessageToUI(message) {
            const messageEl = document.createElement('div');
            messageEl.className = `message ${message.sent ? 'sent' : 'received'}`;
            
            const contentEl = document.createElement('div');
            contentEl.className = 'message-content';

            if (message.content) {
                const textEl = document.createElement('div');
                textEl.textContent = message.content;
                contentEl.appendChild(textEl);
            }

            if (message.file) {
                const attachmentEl = document.createElement('div');
                attachmentEl.className = 'message-attachment';
                
                const iconEl = document.createElement('div');
                iconEl.className = 'message-attachment-icon';
                iconEl.textContent = getFileIcon(message.file.name);
                
                const infoEl = document.createElement('div');
                infoEl.className = 'message-attachment-info';
                
                const nameEl = document.createElement('div');
                nameEl.className = 'text-sm font-medium truncate';
                nameEl.textContent = message.file.name;
                
                const sizeEl = document.createElement('div');
                sizeEl.className = 'text-xs opacity-75';
                sizeEl.textContent = formatFileSize(message.file.size);
                
                infoEl.appendChild(nameEl);
                infoEl.appendChild(sizeEl);
                
                const actionsEl = document.createElement('div');
                actionsEl.className = 'message-attachment-actions';
                
                // Preview button for images
                if (message.file.type.startsWith('image/')) {
                    const previewBtn = document.createElement('button');
                    previewBtn.className = 'text-xs hover:underline focus:outline-none';
                    previewBtn.textContent = 'Preview';
                    previewBtn.addEventListener('click', () => {
                        // Create image preview
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(message.file);
                        img.className = 'max-w-md max-h-96 rounded-lg';
                        
                        // Create modal
                        const modal = document.createElement('div');
                        modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
                        modal.appendChild(img);
                        modal.addEventListener('click', () => modal.remove());
                        
                        document.body.appendChild(modal);
                    });
                    actionsEl.appendChild(previewBtn);
                }
                
                // Download button
                const downloadBtn = document.createElement('button');
                downloadBtn.className = 'text-xs hover:underline focus:outline-none';
                downloadBtn.textContent = 'Download';
                downloadBtn.addEventListener('click', () => {
                    const url = URL.createObjectURL(message.file);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = message.file.name;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                });
                actionsEl.appendChild(downloadBtn);
                
                attachmentEl.appendChild(iconEl);
                attachmentEl.appendChild(infoEl);
                attachmentEl.appendChild(actionsEl);
                contentEl.appendChild(attachmentEl);
            }

            const timeEl = document.createElement('div');
            timeEl.className = 'message-time';
            timeEl.textContent = new Date(message.timestamp).toLocaleTimeString();
            contentEl.appendChild(timeEl);

            messageEl.appendChild(contentEl);
            messagesContainer.appendChild(messageEl);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    });
    </script>
    @endpush
</x-layouts.oca>
