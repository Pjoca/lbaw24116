<div class="frame">
    @foreach ($profiles as $profile)
        <div id="profile-{{ $profile->id }}" class="profile">
            <img src="{{ $profile->image }}" alt="{{ $profile->name }}" />
            <div class="overlay"></div>
            <div class="plus"></div>
        </div>
    @endforeach
</div>
