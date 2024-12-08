@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <!-- Your Groups Section -->
        <h1 class="text-3xl font-bold mb-6">Your Groups</h1>

        <div id="your-groups-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($yourGroups as $group)
                @include('partials.group-card', ['group' => $group])
            @endforeach
        </div>

        <!-- Your Groups Loader -->
        <div id="your-groups-loader" class="hidden text-center py-4">
            <div class="flex justify-center items-center">
                <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-teal-500"></div>
                <span class="ml-4 text-gray-600 font-medium">Loading...</span>
            </div>
        </div>

        <!-- Explore Groups Section -->
        <h1 class="text-3xl font-bold mt-12 mb-6">Explore Groups</h1>

        <div id="explore-groups-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($exploreGroups as $group)
                @include('partials.group-card', ['group' => $group])
            @endforeach
        </div>

        <!-- Explore Groups Loader -->
        <div id="explore-groups-loader" class="hidden text-center py-4">
            <div class="flex justify-center items-center">
                <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-teal-500"></div>
                <span class="ml-4 text-gray-600 font-medium">Loading...</span>
            </div>
        </div>
    </div>
@endsection

@vite('resources/js/infinite-groups.js')
