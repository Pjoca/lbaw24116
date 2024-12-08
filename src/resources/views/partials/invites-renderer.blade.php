@foreach ($invites as $invite)
        @include('partials.invite-item', ['invite' => $invite])
    @endforeach