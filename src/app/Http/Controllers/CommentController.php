<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Método para editar um comentário
    public function edit($id)
{
    // Recupera o comentário pelo ID
    $comment = Comment::findOrFail($id);

    // Verifica se o usuário logado é o autor do comentário
    if ($comment->user_id !== auth()->id()) {
        return redirect()->route('posts.show', ['id' => $comment->post_id])
                         ->with('error', 'Você não tem permissão para editar este comentário.');
    }

    // Verifica se o post_id está presente
    if (!$comment->post_id) {
        return redirect()->route('home')->with('error', 'Post não encontrado para este comentário.');
    }

    // Passa o comentário e o post para a view de edição
    return view('comments.edit', compact('comment'));
}


public function update(Request $request, $id)
{
    $comment = Comment::findOrFail($id);
    $comment->content = $request->input('content');
    $comment->save();

    // Retornar um JSON como resposta
    return response()->json(['success' => true, 'content' => $comment->content]);
}

public function destroy($id)
{
    $comment = Comment::find($id);

    if (!$comment) {
        return response()->json(['success' => false, 'message' => 'Comentário não encontrado'], 404);
    }
    if ($comment->delete()) {
        return response()->json(['success' => true, 'message' => 'Comentário excluído com sucesso']);
    } else {
        return response()->json(['success' => false, 'message' => 'Falha ao excluir o comentário']);
    }
}
public function store(Request $request, $postId)
{
    $request->validate(['content' => 'required|string']);

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
        ],
    ]);
}

}







