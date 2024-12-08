@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6">Explore Groups</h1>

        <!-- Groups Grid -->
        <div id="groups-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @if($groups->isEmpty())
                <p class="col-span-full text-center text-gray-600">No groups found.</p>
            @else
                @foreach ($groups as $group)
                    @include('partials.group-card', ['group' => $group])
                @endforeach
            @endif
        </div>

        <!-- Loader -->
        <div id="loader" class="hidden text-center py-4">
            <div class="flex justify-center items-center">
                <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-teal-500"></div>
                <span class="ml-4 text-gray-600 font-medium">Loading...</span>
            </div>
        </div>
    </div>
@endsection

<!-- Include JavaScript -->
@vite('resources/js/infinite-group.js')
