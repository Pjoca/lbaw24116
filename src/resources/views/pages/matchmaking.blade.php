@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 relative">
    <h1 class="text-3xl font-semibold text-gray-800 text-center mb-6">Matchmaking</h1>
    
    <!-- Perfis -->
    @include('partials.profiles-matchmaking')

    <!-- Detalhes dos Perfis -->
    @include('partials.profile-detail-matchmaking')
</div>
@endsection

<script>
    document.querySelectorAll(".profile").forEach((profile) => {
    profile.addEventListener("click", function () {
        const profileId = this.id.split("-")[1];
        document.querySelector(`#detail-${profileId}`).classList.add("active");
    });
});

document.querySelectorAll(".close").forEach((close) => {
    close.addEventListener("click", function () {
        document.querySelectorAll(".detail").forEach((detail) => {
            detail.classList.remove("active");
        });
    });
});
</script>