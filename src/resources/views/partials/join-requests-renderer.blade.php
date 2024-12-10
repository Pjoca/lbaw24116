    @foreach ($joinRequests as $request)
        @include('partials.join-request-item', ['request' => $request, 'group' => $group])
    @endforeach
