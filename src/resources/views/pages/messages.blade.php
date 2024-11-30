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
                <ul id="message-list" class="space-y-4">
                    <!-- Mensagens irão aparecer dinamicamente aqui -->
                    <li class="flex items-start gap-3">
                        <img src="/path/to/sender-profile.jpg" alt="Sender" class="w-10 h-10 rounded-full">
                        <div class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg max-w-md">
                            Mensagem recebida exemplo...
                        </div>
                    </li>
                    <li class="flex justify-end items-start gap-3">
                        <div class="bg-teal-500 text-white px-4 py-2 rounded-lg max-w-md">
                            Mensagem enviada exemplo...
                        </div>
                        <img src="/path/to/your-profile.jpg" alt="You" class="w-10 h-10 rounded-full">
                    </li>
                </ul>
            </div>
            <!-- Campo de envio -->
            <div class="p-4 border-t border-gray-200">
                <form id="message-form" class="flex items-center gap-4">
                    <input type="text" id="message-input" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Type a message...">
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
