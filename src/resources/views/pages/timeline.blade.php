@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="max-w-3xl mx-auto space-y-8">
            @each('partials.post', $posts, 'post')
        </div>
    </div>
@endsection
