<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow">
            <div class="p-6">
                <h2 class="text-xl font-bold tracking-tight">Chat with Agent</h2>
                <p class="text-sm text-gray-500">Ask questions and get responses from the AI assistant</p>
            </div>
            
            <div class="border-t border-gray-200">
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Chat Messages -->
                        <div class="space-y-4 max-h-[350px] overflow-y-auto pr-4" id="chat-messages">
                            @foreach($this->getChatHistory() as $message)
                                <div class="flex {{ $message['role'] === 'assistant' ? 'justify-start' : 'justify-end' }}">
                                    <div class="max-w-3/4 rounded-lg px-4 py-2 {!! $message['role'] === 'assistant' ? 'bg-gray-100 text-gray-800' : 'bg-blue-500 text-white' !!}">
                                        <div class="text-sm">
                                            @if($message['role'] === 'assistant' && !isset($message['tool_calls']))
                                                <span class="font-medium">Assistant</span>
                                            @elseif($message['role'] === 'assistant' && isset($message['tool_calls']))
                                                <span class="font-medium">Assistant (Tool Call)</span>
                                            @else
                                                <span class="font-medium">You</span>
                                            @endif
                                            @if(isset($message['timestamp']))
                                                <span class="text-xs opacity-50 ml-2">
                                                    {{ \Carbon\Carbon::parse($message['timestamp'])->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="mt-1">{{ $message['content'][0]['text'] ?? $message['content'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- System Messages Panel -->
                        <details class="border rounded-lg overflow-hidden">
                            <summary class="px-4 py-2 bg-gray-50 cursor-pointer hover:bg-gray-100 font-medium text-sm">
                                System Messages
                            </summary>
                            <div class="p-4 bg-white border-t">
                                @foreach($this->getSystemMessages() as $message)
                                    <div class="text-xs text-gray-600 mb-2">
                                        <strong class="font-mono uppercase text-gray-500">{{ $message['role'] }}:</strong>
                                        <p class="mt-1 font-sans">
                                            @if(isset($message['content']))
                                                {{ $message['content'][0]['text'] ?? $message['content'] }}
                                            @else
                                                {{ json_encode($message, JSON_PRETTY_PRINT) }}
                                            @endif
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </details>

                        <!-- Message Input -->
                        <form wire:submit.prevent="sendMessage" class="mt-6">
                            <div class="flex space-x-2">
                                <div class="flex-1">
                                    <textarea
                                        wire:model="message"
                                        rows="3"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 max-h-32 overflow-y-auto"
                                        placeholder="Type your message... (Shift+Enter for new line, Enter to send)"
                                        required
                                        onkeydown="if(event.key === 'Enter' && !event.shiftKey) { 
                                            event.preventDefault(); 
                                            document.getElementById('send-message').click(); 
                                        }">
                                    </textarea>
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <a
                                        id="send-message"
                                        wire:click="sendMessage"
                                        wire:loading.attr="disabled"
                                        :disabled="{{ empty($message) ? 'true' : 'false' }}"
                                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        Send
                                    </a>
                                    <a
                                        wire:click="clearHistory"
                                        wire:confirm="Are you sure you want to clear the chat history?"
                                        class="cursor-pointer inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    >
                                        Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
