@foreach ($requests as $request)
 @include('partials.friend-request-item', ['request' => $request])
@endforeach