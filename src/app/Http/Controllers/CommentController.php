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

        if ($comment->id_user !== auth()->id()) {
            return redirect()->route('posts.show', ['id' => $comment->id_post])
                             ->with('error', 'Você não tem permissão para editar este comentário.');
        }

        if (!$comment->id_post) {
            return redirect()->route('home')->with('error', 'Post não encontrado para este comentário.');
        }

        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        
        // Verifica se o usuário logado é o autor do comentário
        if ($comment->id_user !== auth()->id()) {
            return response()->json(['success' => false, 'error' => 'Sem permissão'], 403);
        }

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

        // Verifica se o usuário logado é o autor do comentário
        if ($comment->id_user !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Sem permissão'], 403);
        }

        if ($comment->delete()) {
            return response()->json(['success' => true, 'message' => 'Comentário excluído com sucesso']);
        } else {
            return response()->json(['success' => false, 'message' => 'Falha ao excluir o comentário']);
        }
    }

    public function store(Request $request, $postId)
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

        // Formata os comentários para a resposta JSON
        $formattedComments = $comments->map(function($comment) {
            return [
                'id' => $comment->id,
                'content' => $comment->content,
                'user_name' => $comment->user->name,
                'user_image' => asset('storage/' . ($comment->user->image_url ?? 'users/images/default.png')),
                'editable' => (Auth::check() && Auth::id() === $comment->id_user),
            ];
        });

        // Verifica se existem mais comentários para carregar
        $totalComments = Comment::where('id_post', $postId)->count();
        $loadedComments = $page * $perPage;
        $hasMore = $loadedComments < $totalComments;

        return response()->json([
            'success' => true,
            'comments' => $formattedComments,
            'hasMore' => $hasMore,
        ]);
    }

}
