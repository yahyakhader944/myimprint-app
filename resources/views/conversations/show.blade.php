<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-xl shadow-md flex flex-col h-[80vh]">

            <!-- Header -->
            @php
                $otherUser = $conversation->worker_id === auth()->id()
                    ? $conversation->investor
                    : $conversation->worker;

                $avatar = $otherUser->workerProfile->avatar ?? $otherUser->investorProfile->avatar ?? null;
                $isWorker = $otherUser->workerProfile !== null;
                $profileRoute = $isWorker ? 'worker-profiles.show' : 'investor-profiles.show';
                $profileModel = $isWorker ? $otherUser->workerProfile : $otherUser->investorProfile;
            @endphp

            <div class="flex items-center justify-between p-4 border-b">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        @if($avatar)
                            <img src="{{ asset('storage/' . $avatar) }}" alt="{{ $otherUser->name }}"
                                class="w-14 h-14 rounded-full object-cover border">
                        @else
                            <div class="w-14 h-14 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">
                                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            @if($profileModel)
                                <a href="{{ route($profileRoute, $profileModel) }}"
                                    class="flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline transition-colors"
                                    title="{{ __('View Profile') }}" target="_blank">
                                    <h2 class="font-semibold text-gray-900">{{ $otherUser->name }}</h2>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600">
                            {{ $isWorker ? __('Worker') : __('Investor') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Messages Container -->
            <div id="messages-container" class="flex-1 overflow-y-auto space-y-4 p-4 bg-gray-50">
                @foreach($conversation->messages as $message)
                    <div id="message-{{ $message->id }}"
                        class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="max-w-xs md:max-w-md px-4 py-2 rounded-lg shadow {{ $message->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 border' }}">

                            <!-- Message Content -->
                            @if($message->body)
                                <p class="whitespace-pre-line">{{ $message->body }}</p>
                            @endif

                            @if($message->attachment)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank"
                                        class="underline text-sm flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        {{ __('View Attachment') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message Form -->
            <form id="message-form" class="border-t p-4">
                @csrf

                <!-- File Display -->
                <div id="file-display" class="mb-3 hidden">
                    <div class="flex items-center justify-between bg-blue-50 border border-blue-200 rounded-lg p-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span id="file-name" class="text-sm text-blue-800 font-medium truncate max-w-xs"></span>
                        </div>
                        <button type="button" onclick="removeSelectedFile()"
                            class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50 transition-colors duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="flex items-center gap-3">
                    <textarea name="body" id="message-body" rows="1" placeholder="{{ __('Type your message...') }}"
                        class="flex-1 border rounded-xl p-2 resize-none" oninput="updateSendButton()"></textarea>

                    <input type="file" name="attachment" id="message-attachment" accept="image/*,.pdf,.doc,.docx,.zip"
                        class="hidden" onchange="handleFileSelect()">

                    <label for="message-attachment" title="{{ __('Add attachment') }}"
                        class="cursor-pointer p-2 text-gray-600 hover:text-blue-600 rounded-full hover:bg-blue-50 flex-shrink-0 transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </label>

                    <button type="button" id="send-button" onclick="sendMessage()"
                        class="bg-gray-300 text-gray-500 px-4 py-2 rounded-xl flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-300 justify-center">
                        <span id="send-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </span>
                        <span id="send-spinner" class="hidden">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </span>
                        {{ __('Send') }}
                    </button>
                </div>

                <!-- Error Message -->
                <div id="form-error" class="text-red-600 text-sm mt-2 hidden">
                    {{ __('Please enter a message or attach a file.') }}
                </div>
            </form>
        </div>
    </div>

    <script>
        let isSending = false;

        // Page load event
        document.addEventListener('DOMContentLoaded', function () {
            scrollToBottom();
            updateSendButton();
            startAutoRefresh();
            setupEnterKeyHandler();
        });

        // Page before unload event
        window.addEventListener('beforeunload', function () {
            isRefreshing = false;
        });

        // Setup Enter key handler for message sending
        function setupEnterKeyHandler() {
            const textarea = document.getElementById('message-body');

            textarea.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    if (event.shiftKey) {
                        // Shift + Enter: Allow new line
                        return;
                    } else {
                        // Enter alone: Send message
                        event.preventDefault();

                        if (!isSending) {
                            sendMessage();
                        }
                    }
                }
            });
        }

        // Send message via AJAX
        async function sendMessage() {
            if (isSending || !validateForm()) {
                return;
            }

            isSending = true;
            showSendingState();
            disableForm();

            try {
                const formData = new FormData();
                formData.append('body', document.getElementById('message-body').value);
                formData.append('_token', '{{ csrf_token() }}');

                const attachment = document.getElementById('message-attachment').files[0];
                if (attachment) {
                    formData.append('attachment', attachment);
                }

                const response = await fetch('{{ route("messages.store", $conversation) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Clear form and add new message to the list
                    clearForm();
                    addNewMessage(result.message);
                    scrollToBottom();

                } else {
                    // Handle validation errors from server
                    if (result.message) {
                        showError(result.message);
                    } else {
                        throw new Error('Message sending failed');
                    }
                }

            } catch (error) {
                console.error('Error sending message:', error);
                showError('Failed to send message. Please try again.');
            } finally {
                isSending = false;
                hideSendingState();
                enableForm();
            }
        }

        // Show sending state with spinner
        function showSendingState() {
            document.getElementById('send-icon').classList.add('hidden');
            document.getElementById('send-spinner').classList.remove('hidden');
        }

        // Hide sending state
        function hideSendingState() {
            document.getElementById('send-icon').classList.remove('hidden');
            document.getElementById('send-spinner').classList.add('hidden');
        }

        // Disable form during sending
        function disableForm() {
            document.getElementById('message-body').disabled = true;
            document.getElementById('message-attachment').disabled = true;
            document.getElementById('send-button').disabled = true;
        }

        // Enable form after sending
        function enableForm() {
            document.getElementById('message-body').disabled = false;
            document.getElementById('message-attachment').disabled = false;
            updateSendButton();
        }

        // Validate form
        function validateForm() {
            const messageBody = document.getElementById('message-body');
            const attachment = document.getElementById('message-attachment');
            const errorDiv = document.getElementById('form-error');

            const hasContent = messageBody.value.trim().length > 0 || attachment.files.length > 0;

            if (!hasContent) {
                errorDiv.classList.remove('hidden');
                messageBody.focus();
                return false;
            }

            errorDiv.classList.add('hidden');
            return true;
        }

        // Clear form after successful send
        function clearForm() {
            document.getElementById('message-body').value = '';
            document.getElementById('message-body').style.height = 'auto';
            removeSelectedFile();
            updateSendButton();
            hideError();
        }

        // Show error message
        function showError(message) {
            const errorDiv = document.getElementById('form-error');
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
        }

        // Hide error message
        function hideError() {
            document.getElementById('form-error').classList.add('hidden');
        }

        // Scroll to bottom function
        function scrollToBottom() {
            const container = document.getElementById('messages-container');
            container.scrollTop = container.scrollHeight;
        }

        // Update send button function
        function updateSendButton() {
            const messageBody = document.getElementById('message-body');
            const attachment = document.getElementById('message-attachment');
            const sendButton = document.getElementById('send-button');
            const hasContent = messageBody.value.trim().length > 0 || attachment.files.length > 0;

            sendButton.disabled = !hasContent || isSending;

            if (hasContent && !isSending) {
                sendButton.classList.remove('bg-gray-300', 'text-gray-500');
                sendButton.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
            } else {
                sendButton.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
                sendButton.classList.add('bg-gray-300', 'text-gray-500');
            }
        }

        // Handle attachment file function
        function handleFileSelect() {
            const fileInput = document.getElementById('message-attachment');
            const fileDisplay = document.getElementById('file-display');
            const fileName = document.getElementById('file-name');

            if (fileInput.files.length > 0) {
                fileName.textContent = fileInput.files[0].name;
                fileDisplay.classList.remove('hidden');
            } else {
                fileDisplay.classList.add('hidden');
            }
            updateSendButton();
            hideError();
        }

        // Remove attachment file function
        function removeSelectedFile() {
            document.getElementById('message-attachment').value = '';
            document.getElementById('file-display').classList.add('hidden');
            updateSendButton();
        }

        // Auto size change for input textarea
        const textarea = document.getElementById('message-body');
        if (textarea) {
            textarea.addEventListener('input', function () {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
                updateSendButton();
                hideError();
            });
        }

        // Auto messages refresh function
        let lastMessageId = {{ $conversation->messages->last()?->id ?? 0 }};
        let isRefreshing = true;

        function startAutoRefresh() {
            // Refresh every 3 seconds
            setInterval(() => {
                if (isRefreshing && !isSending) {
                    checkNewMessages();
                }
            }, 3000);
        }

        // Check for new messages function
        async function checkNewMessages() {
            try {
                const response = await fetch(`{{ route('messages.index', $conversation) }}?after_id=${lastMessageId}`);
                const messages = await response.json();

                if (messages.length > 0) {
                    addNewMessages(messages);
                    lastMessageId = messages[messages.length - 1].id;
                    scrollToBottom();
                }
            } catch (error) {
                console.log('Error checking messages:', error);
            }
        }

        // Add new message to the list
        function addNewMessage(messageData) {
            const container = document.getElementById('messages-container');
            const messageElement = createMessageElement(messageData);
            container.insertAdjacentHTML('beforeend', messageElement);
        }

        // Add multiple new messages
        function addNewMessages(messages) {
            const container = document.getElementById('messages-container');

            messages.forEach(message => {
                if (!document.getElementById(`message-${message.id}`)) {
                    const messageElement = createMessageElement(message);
                    container.insertAdjacentHTML('beforeend', messageElement);
                }
            });
        }

        // Create new message element function
        function createMessageElement(message) {
            const isMine = message.sender_id === {{ auth()->id() }};
            const messageClass = isMine ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 border';

            let attachmentHtml = '';

            if (message.attachment) {
                attachmentHtml = `
                    <div class="mt-2">
                        <a href="/storage/${message.attachment}" target="_blank"
                            class="underline text-sm flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            View Attachment
                        </a>
                    </div>
                `;
            }

            return `
                <div id="message-${message.id}" class="flex ${isMine ? 'justify-end' : 'justify-start'} animate-fade-in">
                    <div class="max-w-xs md:max-w-md px-4 py-2 rounded-lg shadow ${messageClass}">
                        ${message.body ? `<p class="whitespace-pre-line">${message.body}</p>` : ''}
                        ${attachmentHtml}
                    </div>
                </div>
            `;
        }
    </script>
</x-app-layout>
