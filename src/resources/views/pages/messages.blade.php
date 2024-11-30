@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="grid grid-cols-4 gap-4">
        <!-- Sidebar -->
        <aside class="col-span-1 bg-gray-200 p-4 rounded-lg shadow-md">
            <h3 class="font-bold text-lg mb-4">Mensagens</h3>
            <ul class="space-y-2">
                <li>
                    <a href="#" class="flex items-center gap-2 hover:bg-gray-300 p-2 rounded-lg transition">
                        <img src="https://via.placeholder.com/40" alt="User" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-medium text-sm">Rui Borges</p>
                            <p class="text-xs text-gray-500">Última mensagem</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-2 hover:bg-gray-300 p-2 rounded-lg transition">
                        <img src="https://via.placeholder.com/40" alt="User" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-medium text-sm">Pedro Paixão</p>
                            <p class="text-xs text-gray-500">A conversa de vídeo terminou</p>
                        </div>
                    </a>
                </li>
                <!-- Adicione mais contatos aqui -->
            </ul>
        </aside>

        <!-- Main Chat -->
        <section class="col-span-3 bg-white p-4 rounded-lg shadow-md">
            <header class="flex items-center justify-between border-b pb-4 mb-4">
                <div class="flex items-center gap-2">
                    <img src="https://via.placeholder.com/50" alt="User" class="w-12 h-12 rounded-full">
                    <div>
                        <p class="font-bold text-lg">Tiago Torrado</p>
                        <p class="text-sm text-gray-500">Online</p>
                    </div>
                </div>
                <button class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition">Encerrar</button>
            </header>

            <div class="space-y-4 h-[400px] overflow-y-auto bg-gray-100 p-4 rounded-lg">
                <!-- Messages -->
                <div class="flex items-start gap-4">
                    <img src="https://via.placeholder.com/40" alt="User" class="w-10 h-10 rounded-full">
                    <div class="bg-gray-200 p-3 rounded-lg">
                        <p class="text-sm">siga siga</p>
                    </div>
                </div>
                <div class="flex items-end justify-end gap-4">
                    <div class="bg-teal-500 text-white p-3 rounded-lg">
                        <p class="text-sm">siga siga meu menino</p>
                    </div>
                </div>
            </div>

            <form class="mt-4 flex items-center gap-4">
                <input type="text" placeholder="Escreva uma mensagem" class="w-full border border-gray-300 rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500">
                <button type="submit" class="bg-teal-500 text-white py-2 px-4 rounded-lg hover:bg-teal-600 transition">Enviar</button>
            </form>
        </section>
    </div>
</div>
@endsection
