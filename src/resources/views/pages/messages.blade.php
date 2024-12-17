@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-4">
    <div class="flex flex-col lg:flex-row gap-4">
        <!-- Lista de conversas -->
        <div class="w-full lg:w-1/3 bg-white shadow-md rounded-lg">
            <div class="bg-teal-600 text-white px-4 py-3 font-semibold rounded-t-lg flex justify-between items-center">
                Conversations
                <button 
                    id="new-conversation-btn" 
                    class="bg-teal-500 text-white px-2 py-1 rounded-lg hover:bg-teal-700">
                    New Conversation
                </button>
            </div>
            <ul id="conversation-list" class="divide-y divide-gray-200 max-h-[60vh] overflow-y-auto">
                @forelse ($contacts as $contact)
                    <li class="p-4 flex items-center gap-4 hover:bg-gray-100 cursor-pointer conversation-item" data-contact-id="{{ $contact->id }}">
                        <img src="{{ asset('storage/' . $contact['image_url']) }}" alt="{{ $contact['name'] }}" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $contact->name }}</p>
                        </div>
                    </li>
                @empty
                    <li class="no-conversations text-gray-500 italic p-4">No conversations yet. Start one!</li>
                @endforelse
            </ul>

        </div>

        <!-- Painel de mensagens -->
        <div class="w-full lg:w-2/3 bg-white shadow-md rounded-lg flex flex-col">
            <div id="contact-name-panel" class="bg-teal-600 text-white px-4 py-3 font-semibold rounded-t-lg">
                Select a conversation
            </div>
            <div id="message-panel" class="flex-1 p-4 overflow-y-auto">
                <div id="placeholder" class="flex items-center justify-center h-full text-gray-500 italic">
                    Select a conversation to view messages.
                </div>
                <ul id="message-list" class="space-y-4 hidden max-h-[60vh] overflow-y-auto">
                    <!-- Messages will appear here -->
                </ul>
            </div>

            <div id="message-input-panel" class="p-4 border-t border-gray-200 hidden">
                <form id="message-form" class="flex items-center gap-4">
                    <input 
                        type="text" 
                        id="message-input" 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" 
                        placeholder="Type a message..." 
                        required>
                    <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="new-conversation-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-3/4 max-w-lg max-h-[80vh] overflow-y-auto">
        <h3 class="text-xl font-semibold mb-4">Start a New Conversation</h3>
        <ul id="friend-list" class="divide-y divide-gray-200">
            @foreach ($friends as $friend)
            <li class="p-4 hover:bg-gray-100 cursor-pointer friend-item flex items-center gap-4" data-friend-id="{{ $friend->id }}">
                <img src="{{ asset('storage/' . ($friend['image_url'] ?? 'users/images/default.png')) }}" alt="{{ $friend['name'] }}" class="w-12 h-12 rounded-full object-cover">
                <span class="ml-4">{{ $friend->name }}</span>
            </li>
            @endforeach
        </ul>
        <div class="mt-4 flex justify-end">
            <button id="close-modal-btn" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Importar o arquivo JavaScript -->
@vite('resources/js/messages.js')
@endsection
