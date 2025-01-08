<x-layouts.club>
    <x-slot:content>
        <div class="h-[calc(100vh-4rem)] bg-gray-50 flex flex-col">
            <!-- Chat Header -->
            <div class="bg-white border-b px-6 py-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold text-xl">
                        OCA
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Office of Co-curricular Activities</h2>
                        <p class="text-sm text-gray-500">Support Chat</p>
                    </div>
                </div>
                <div class="text-sm text-green-500 flex items-center">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                    Online
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="flex-1 overflow-y-auto px-6 py-4" id="messageContainer">
                <!-- Messages will be loaded here -->
            </div>

            <!-- Chat Input -->
            <div class="bg-white border-t px-6 py-4">
                <form id="messageForm" class="space-y-4">
                    @csrf
                    <!-- File Preview -->
                    <div id="previewContainer" class="hidden">
                        <div class="flex flex-wrap gap-2 p-3 bg-gray-50 rounded-lg">
                            <div id="filePreview" class="flex flex-wrap gap-2"></div>
                        </div>
                    </div>

                    <!-- Emoji Picker -->
                    <div id="emojiPicker" class="hidden absolute bottom-full right-0 mb-2 bg-white border rounded-lg shadow-lg p-2 w-64">
                        <div class="grid grid-cols-8 gap-1 max-h-40 overflow-y-auto">
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">😊</button>
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">😂</button>
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">❤️</button>
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">👍</button>
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">🎉</button>
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">🔥</button>
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">🤔</button>
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">😎</button>
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">🥳</button>
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">😍</button>
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">🙌</button>
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">✨</button>
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">💯</button>
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">👏</button>
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">🎈</button>
                            <button type="button" class="p-1 hover:bg-gray-100 rounded text-xl">🌟</button>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <div class="flex-1 relative">
                            <input type="text" 
                                   id="messageInput" 
                                   name="message" 
                                   class="w-full px-4 py-2 pr-24 border rounded-full focus:outline-none focus:border-blue-500" 
                                   placeholder="Type your message...">
                            
                            <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center space-x-1">
                                <button type="button" id="emojiButton" class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                                <button type="button" id="attachmentButton" class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2">
                            <span>Send</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <input type="file" 
                           id="attachmentInput" 
                           name="attachment[]" 
                           multiple 
                           class="hidden"
                           accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">
                </form>
            </div>
        </div>
    </x-slot:content>

    @push('styles')
    <style>
        .message {
            @apply mb-4 flex flex-col max-w-[70%];
        }

        .message.sent {
            @apply ml-auto;
        }

        .message.received {
            @apply mr-auto;
        }

        .message-content {
            @apply px-4 py-2 rounded-2xl text-sm;
        }

        .message.sent .message-content {
            @apply bg-blue-600 text-white rounded-br-none;
        }

        .message.received .message-content {
            @apply bg-blue-100 text-gray-900 rounded-bl-none;
        }

        .message-time {
            @apply text-xs text-gray-500 mt-1;
        }

        .message-attachments {
            @apply mt-2 space-y-2;
        }

        .message-attachment {
            @apply rounded-lg overflow-hidden;
        }

        .message-attachment img {
            @apply max-w-full h-auto rounded-lg;
        }

        .message-attachment a {
            @apply flex items-center space-x-2 p-2 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors;
        }

        .message.sent .message-attachment a {
            @apply bg-blue-700 hover:bg-blue-800 text-white;
        }

        .attachment-preview {
            @apply relative group;
        }

        .attachment-preview img {
            @apply w-16 h-16 object-cover rounded-lg;
        }

        .attachment-preview span {
            @apply block text-sm truncate max-w-[120px];
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageContainer = document.getElementById('messageContainer');
            const messageForm = document.getElementById('messageForm');
            const messageInput = document.getElementById('messageInput');
            const attachmentInput = document.getElementById('attachmentInput');
            const attachmentButton = document.getElementById('attachmentButton');
            const emojiButton = document.getElementById('emojiButton');
            const emojiPicker = document.getElementById('emojiPicker');
            const previewContainer = document.getElementById('previewContainer');
            const filePreview = document.getElementById('filePreview');
            
            let lastMessageId = 0;

            // Load initial messages
            loadMessages();

            // Set up message polling
            setInterval(loadMessages, 3000);

            function loadMessages() {
                fetch('/club/chat/messages')
                    .then(response => response.json())
                    .then(data => {
                        // Only update if there are new messages
                        if (data.length === 0 || data[data.length - 1].id === lastMessageId) return;
                        
                        messageContainer.innerHTML = '';
                        data.forEach(message => {
                            const messageElement = createMessageElement(message);
                            messageContainer.appendChild(messageElement);
                        });
                        
                        lastMessageId = data[data.length - 1].id;
                        messageContainer.scrollTop = messageContainer.scrollHeight;
                    });
            }

            function createMessageElement(message) {
                const div = document.createElement('div');
                div.className = `message ${message.sender_id === {{ Auth::id() }} ? 'sent' : 'received'}`;
                
                let html = `<div class="message-content">${message.content}</div>`;
                
                if (message.attachments && message.attachments.length > 0) {
                    html += '<div class="message-attachments">';
                    message.attachments.forEach(attachment => {
                        if (attachment.type.startsWith('image/')) {
                            html += `
                                <div class="message-attachment">
                                    <img src="${attachment.url}" alt="${attachment.name}" onclick="window.open('${attachment.url}', '_blank')">
                                </div>`;
                        } else {
                            html += `
                                <div class="message-attachment">
                                    <a href="${attachment.url}" target="_blank" class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                        <span>${attachment.name}</span>
                                    </a>
                                </div>`;
                        }
                    });
                    html += '</div>';
                }
                
                html += `<div class="message-time">${new Date(message.created_at).toLocaleTimeString()}</div>`;
                div.innerHTML = html;
                
                return div;
            }

            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                if (!messageInput.value.trim() && !attachmentInput.files.length) return;
                
                const formData = new FormData(this);
                
                fetch('/club/chat/send', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(message => {
                    const messageElement = createMessageElement(message);
                    messageContainer.appendChild(messageElement);
                    messageContainer.scrollTop = messageContainer.scrollHeight;
                    messageInput.value = '';
                    attachmentInput.value = '';
                    previewContainer.classList.add('hidden');
                    filePreview.innerHTML = '';
                });
            });

            attachmentButton.addEventListener('click', () => {
                attachmentInput.click();
            });

            attachmentInput.addEventListener('change', function() {
                filePreview.innerHTML = '';
                if (this.files.length > 0) {
                    previewContainer.classList.remove('hidden');
                    Array.from(this.files).forEach(file => {
                        const preview = document.createElement('div');
                        preview.className = 'attachment-preview';
                        
                        if (file.type.startsWith('image/')) {
                            const img = document.createElement('img');
                            img.src = URL.createObjectURL(file);
                            preview.appendChild(img);
                        }
                        
                        const name = document.createElement('span');
                        name.textContent = file.name;
                        preview.appendChild(name);
                        
                        filePreview.appendChild(preview);
                    });
                } else {
                    previewContainer.classList.add('hidden');
                }
            });

            emojiPicker.addEventListener('click', (e) => {
                if (e.target.tagName === 'BUTTON') {
                    messageInput.value += e.target.textContent;
                    emojiPicker.classList.add('hidden');
                    messageInput.focus();
                }
            });

            emojiButton.addEventListener('click', (e) => {
                e.stopPropagation();
                emojiPicker.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!emojiPicker.contains(e.target) && e.target !== emojiButton) {
                    emojiPicker.classList.add('hidden');
                }
            });
        });
    </script>
    @endpush
</x-layouts.club>
