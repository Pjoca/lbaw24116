@foreach ($invites as $invite)
    @include ('partials.user-invites', ['invite' => $invite])
@endforeach