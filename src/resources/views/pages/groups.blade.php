@extends('layouts.app')

@section('content')

    
    
    <div class="container mx-auto py-4">
        
        @if(Auth::check())
        @include('modals.create-group-modal')
        <div class="max-w-5xl mx-auto flex justify-end mb-4">
            <button 
                onclick="openModal('create-group-modal')" 
                class="bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded-lg"
            >
                + Create Group
            </button>
        </div>
        @endif

        <!-- Tab Navigation Bar -->
        <div class="max-w-5xl mx-auto shadow-lg rounded-lg pt-2  mb-4">
            <div class="flex justify-center">
                <!-- Your Groups Tab -->
                <button 
                    id="your-groups-tab" 
                    onclick="showSection('your-groups')" 
                    class="w-1/2 text-center text-lg font-bold py-2 border-b-4 border-teal-500">
                    Your Groups
                </button>

                <!-- Explore Groups Tab -->
                <button 
                    id="explore-groups-tab" 
                    onclick="showSection('explore-groups')" 
                    class="w-1/2 text-center text-lg font-bold py-2 border-b-4 border-gray-300 hover:border-teal-500">
                    Explore Groups
                </button>
            </div>
        </div>
        

        <!-- Your Groups Section -->
        <div id="your-groups-section" class="max-w-5xl mx-auto">

            <div id="your-groups-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($yourGroups as $group)
                    @include('partials.group-card', ['group' => $group])
                @endforeach
            </div>

            <!-- Your Groups Loader -->
            <div id="your-groups-loader" class="hidden text-center py-2">
                <div class="flex justify-center items-center">
                    <div class="animate-spin rounded-full h-6 w-6 border-t-2 border-b-2 border-teal-500"></div>
                    <span class="ml-2 text-gray-600 font-medium">Loading...</span>
                </div>
            </div>
        </div>

        <!-- Explore Groups Section -->
        <div id="explore-groups-section" class="hidden max-w-5xl mx-auto">

            <div id="explore-groups-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($exploreGroups as $group)
                    @include('partials.group-card', ['group' => $group])
                @endforeach
            </div>

            <!-- Explore Groups Loader -->
            <div id="explore-groups-loader" class="hidden text-center py-2">
                <div class="flex justify-center items-center">
                    <div class="animate-spin rounded-full h-6 w-6 border-t-2 border-b-2 border-teal-500"></div>
                    <span class="ml-2 text-gray-600 font-medium">Loading...</span>
                </div>
            </div>
        </div>

    </div>
@endsection

@vite('resources/js/infinite-group.js')
@vite('resources/js/group.js')
@vite('resources/js/comments.js')
