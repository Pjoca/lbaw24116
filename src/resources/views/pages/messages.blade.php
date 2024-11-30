@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-4">
    <div class="flex flex-col lg:flex-row gap-4">
        <!-- Lista de conversas -->
        <div class="w-full lg:w-1/3 bg-white shadow-md rounded-lg">
            <div class="bg-teal-600 text-white px-4 py-3 font-semibold rounded-t-lg">
                Conversations
            </div>
            <ul id="conversation-list" class="divide-y divide-gray-200">
                @foreach ($contacts as $contact)
                <li class="p-4 flex items-center gap-4 hover:bg-gray-100 cursor-pointer conversation-item" data-contact-id="{{ $contact->id }}">

                    <img src="{{ asset('storage/' . $contact['image_url']) }}" alt="{{ $contact['name'] }}" class="w-12 h-12 rounded-full object-cover">

                    <div>
                        <p class="font-semibold text-gray-800">{{ $contact->name }}</p>
                        <p class="text-sm text-gray-500">Last message preview...</p>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Painel de mensagens -->
        <div class="w-full lg:w-2/3 bg-white shadow-md rounded-lg flex flex-col">
            <div class="bg-teal-600 text-white px-4 py-3 font-semibold rounded-t-lg">
                Messages
            </div>
            <div id="message-panel" class="flex-1 p-4 overflow-y-auto">
            <div id="placeholder" class="flex items-center justify-center h-full text-gray-500 italic">
                Select a conversation to view messages.
            </div>
    <ul id="message-list" class="space-y-4 hidden">
        <!-- Mensagens irão aparecer dinamicamente aqui -->
    </ul>
</div>

            <!-- Campo de envio -->
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

<!-- Importar o arquivo JavaScript -->
@vite('resources/js/messages.js')
@endsection
