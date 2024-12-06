@foreach ($members as $member)
    @include('partials.member-item', ['member' => $member, 'group' => $group])
@endforeach
