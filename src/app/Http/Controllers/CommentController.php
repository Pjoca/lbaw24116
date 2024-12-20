<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function edit($id)
    {
        $comment = Comment::findOrFail($id);

        $this->authorize('update', $comment);

        if (!$comment->id_post) {
            return redirect()->route('home')->with('error', 'Post não encontrado para este comentário.');
        }

        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, $id)
{
    $comment = Comment::findOrFail($id);

    $this->authorize('update', $comment);

    $comment->content = $request->input('content');
    $comment->save();

    return response()->json(['success' => true, 'content' => $comment->content]);
}

public function destroy($id)
{
    $comment = Comment::find($id);

    if (!$comment) {
        return response()->json(['success' => false, 'message' => 'Comentário não encontrado'], 404);
    }

    $this->authorize('update', $comment);

    if ($comment->delete()) {
        return response()->json(['success' => true, 'message' => 'Comentário excluído com sucesso']);
    } else {
        return response()->json(['success' => false, 'message' => 'Falha ao excluir o comentário']);
    }
}


    public function store(Request $request, $postId, NotificationController $notificationController)
{
    $validator = \Validator::make($request->all(), [
        'content' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    $comment = new Comment([
        'content' => $request->input('content'),
        'id_user' => auth()->id(),
        'id_post' => $postId,
    ]);
    $comment->save();

    // Notifica o autor do post
    $notificationController->addNotification(new Request([
        'id_user' => $comment->post->id_user,
        'message_content' => auth()->user()->name . ' commented on your post.',
        'onclick_url' => 'javascript:goWithCommand(\'/timeline/personalized/\', \'showPostViewModal(' . $comment->post->id . ');\')',
    ]));

    return response()->json([
        'success' => true,
        'comment' => [
            'id' => $comment->id,
            'content' => $comment->content,
            'user_name' => $comment->user->name,
            'user_image' => asset('storage/' . ($comment->user->image_url ?? 'users/images/default.png')),
            'editable' => (auth()->id() === $comment->id_user)
        ],
    ]);
}

public function loadComments(Request $request, $postId)
    {
        $page = $request->input('page', 1);
        $perPage = 3;

        // Ordena pelos IDs dos comentários para manter a ordem de criação (assumindo que IDs crescentes correspondem a comentários mais recentes)
        $comments = Comment::where('id_post', $postId)
                          ->orderBy('id', 'asc') // Você pode mudar para 'desc' se preferir a ordem inversa
                          ->skip(($page - 1) * $perPage)
                          ->take($perPage)
                          ->get();

        // Verifica se existem mais comentários para carregar
        $totalComments = Comment::where('id_post', $postId)->count();
        $loadedComments = $page * $perPage;
        $hasMore = $loadedComments < $totalComments;

        // Formata os comentários para a resposta JSON
        $formattedComments = $comments->map(function($comment) use ($hasMore) {
            return [
                'id' => $comment->id,
                'content' => $comment->content,
                'user_name' => $comment->user->name,
                'user_image' => asset('storage/' . ($comment->user->image_url ?? 'users/images/default.png')),
                'editable' => (Auth::check() && Auth::id() === $comment->id_user),
                'hasMorePages' => $hasMore,
            ];
        });

        // Verifica se existem mais comentários para carregar
        $totalComments = Comment::where('id_post', $postId)->count();
        $loadedComments = $page * $perPage;
        $hasMore = $loadedComments < $totalComments;

        return response()->json([
            'success' => true,
            'comments' => $formattedComments,
            'hasMorePages' => $hasMore,
        ]);
    }

}
