<div class="flex h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Users List -->
    <div class="w-1/4 border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex flex-col">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Chats</h2>
        </div>
        <div class="flex-1 overflow-y-auto">
            @forelse($users as $user)
                <button
                    wire:click="selectUser({{ $user->id }})"
                    class="w-full p-4 text-left hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ $selectedUserId == $user->id ? 'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500' : '' }}"
                >
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 font-semibold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </button>
            @empty
                <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                    No users available
                </div>
            @endforelse
        </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 flex flex-col">
        @if($selectedUser)
            <!-- Chat Header -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 font-semibold">
                        {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $selectedUser->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Online</p>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-900" id="messages-container">
                @forelse($messages as $message)
                    <div class="flex {{ $message->send_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->send_id == Auth::id() ? 'bg-blue-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700' }}">
                            <p class="text-sm">{{ $message->message }}</p>
                            <p class="text-xs mt-1 {{ $message->send_id == Auth::id() ? 'text-blue-100' : 'text-gray-500 dark:text-gray-400' }}">
                                {{ $message->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                        No messages yet. Start the conversation!
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <form wire:submit.prevent="sendMessage" class="flex gap-2">
                    <input
                        type="text"
                        wire:model="message"
                        placeholder="Type a message..."
                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    />
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                    >
                        Send
                    </button>
                </form>
            </div>
        @else
            <!-- Empty State -->
            <div class="flex-1 flex items-center justify-center bg-gray-50 dark:bg-gray-900">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No chat selected</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Select a user from the list to start chatting</p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('message-sent', () => {
            setTimeout(() => {
                const container = document.getElementById('messages-container');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }, 100);
        });
    });

    // Auto-scroll to bottom when new messages arrive
    document.addEventListener('livewire:updated', () => {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });
</script>
