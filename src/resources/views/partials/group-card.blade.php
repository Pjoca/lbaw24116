<div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="h-32 bg-gray-200 flex items-center justify-center">
        <img src="{{ asset('storage/' . ($group->image_url ?? 'images/default-group.png')) }}" alt="{{ $group->name }}" class="h-full w-full object-cover">
    </div>
    <div class="p-4">
        <h2 class="text-lg font-semibold">{{ $group->name }}</h2>
        <p class="text-sm text-gray-600 mt-1 line-clamp-3">{{ $group->description }}</p>

        <a href="{{ route('groups.show', $group->id) }}" 
           class="mt-4 block bg-teal-500 text-white text-center py-2 rounded hover:bg-teal-600 transition">
            View Group
        </a>
    </div>
</div>
