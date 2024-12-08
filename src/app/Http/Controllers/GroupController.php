<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// import Group model

use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\GroupMembershipRequest;
use Log;

class GroupController extends Controller
{
/**
 * Display the group details page.
 */
public function show($id)
{
    // Load the group along with relationships
    $group = Group::with(['owner'])->findOrFail($id);

    $user = Auth::user();

    // Check if the authenticated user has a pending membership request
    $hasRequestedMembership = GroupMembershipRequest::where('id_user', $user->id)
        ->where('id_group', $group->id)
        ->where('request_status', 'pending')
        ->exists();

    // Check if the authenticated user is a member of the group
    $isMember = GroupMembership::where('id_user', $user->id)
        ->where('id_group', $group->id)
        ->exists();

    // If not a member, restrict access to posts
    $posts = collect();
    if ($isMember) {
        $posts = $group->posts()
            ->where('visibility', 'group')
            ->where('id_group', $id)
            ->latest()
            ->get();
    }

    return view('pages.group', compact(
        'group',
        'isMember',
        'posts',
        'hasRequestedMembership',
    ));
}

public function index(Request $request)
{
    if ($request->ajax()) {
        $groups = Group::orderBy('created_at', 'desc')->paginate(4);
        $html = '';

        // Loop through each group and pass it to the group-card partial
        foreach ($groups as $group) {
            $html .= view('partials.group-card', ['group' => $group])->render();
        }

        return response()->json(['html' => $html]);
    }

    $groups = Group::orderBy('created_at', 'desc')->paginate(12);
    return view('pages.groups', compact('groups'));
}



}
