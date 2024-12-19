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

    // Check if the request is an AJAX request to fetch group info for editing
    if (request()->ajax()) {
        return response()->json([
            'name' => $group->name,
            'description' => $group->description,
            'visibility' => $group->visibility,
            'image_url' => $group->image_url,
        ]);
    }

    // Check if the authenticated user has a pending membership request
    $hasRequestedMembership = GroupMembershipRequest::where('id_user', $user->id)
        ->where('id_group', $group->id)
        ->where('request_status', 'pending')
        ->where('is_invite',false)
        ->exists();

    // Check if the authenticated user has a pending invite
    $invite = GroupMembershipRequest::where('id_user', $user->id)
        ->where('id_group', $group->id)
        ->where('request_status', 'pending')
        ->where('is_invite', true)
        ->first();

    $hasInvite = !is_null($invite);
    $inviteId = $invite ? $invite->id : null;

    // Check if the authenticated user is a member of the group
    $isMember = GroupMembership::where('id_user', $user->id)
        ->where('id_group', $group->id)
        ->exists();

    // If the user is a member, load the group posts
    $posts = $isMember 
        ? $group->posts()->where('visibility', 'group')->latest()->get() 
        : collect();

    return view('pages.group', compact(
        'group',
        'isMember',
        'posts',
        'hasRequestedMembership',
        'hasInvite',
        'inviteId'
    ));
}



public function index(Request $request)
{
    if ($request->ajax() && $request->has('section')) {
        $section = $request->get('section');
        
        if ($section === 'your-groups') {
            $groups = Group::whereHas('memberships', function($query) {
                $query->where('id_user', auth()->id());
            })->orderBy('created_at', 'desc')->paginate(12);
        } elseif ($section === 'explore-groups') {
            $groups = Group::whereDoesntHave('memberships', function($query) {
                $query->where('id_user', auth()->id());
            })->orderBy('created_at', 'desc')->paginate(12);
        }

        // Render each group as a separate partial with $group variable
        $html = '';
        foreach ($groups as $group) {
            $html .= view('partials.group-card', ['group' => $group])->render();
        }

        return response()->json(['html' => $html]);
    }

    $yourGroups = Group::whereHas('memberships', function($query) {
        $query->where('id_user', auth()->id());
    })->orderBy('created_at', 'desc')->paginate(12);

    $exploreGroups = Group::whereDoesntHave('memberships', function($query) {
        $query->where('id_user', auth()->id());
    })->orderBy('created_at', 'desc')->paginate(12);

    return view('pages.groups', compact('yourGroups', 'exploreGroups'));
}


public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'visibility' => 'required|in:public,private',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image
    ]);

    // Handle image upload
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('group-images', 'public');
    }

    Log::info('Image path: ' . $imagePath);

    $group = Group::create([
        'name' => $request->name,
        'description' => $request->description,
        'visibility' => $request->visibility,
        'image_url' => $imagePath,
        'id_owner' => Auth::id(),
        'created_at' => now(),
    ]);

    return redirect()->route('groups.show', $group->id)
        ->with('success', 'Group created successfully!');
}



    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        // Check if the user is the owner of the group
        $this->authorize('owner', $group);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'visibility' => 'required|in:public,private',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Update to accept image files
        ]);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('group_images', 'public');
            $group->image_url = $path;
        }

        $group->update([
            'name' => $request->name,
            'description' => $request->description,
            'visibility' => $request->visibility,
            'image_url' => $group->image_url, // Keep the image URL intact
        ]);

        return redirect()->route('groups.show', $group->id)
            ->with('success', 'Group updated successfully!');
    }

}
