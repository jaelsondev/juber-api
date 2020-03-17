<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\UserPost;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostController extends Controller
{
  protected $user;

  public function __construct()
  {
    $this->user = JWTAuth::parseToken()->authenticate();
  }

  public function index()
  {
    return Post::with(['user', 'likes'])->get();
  }
  public function show($id)
  {
    return Post::with(['user', 'likes'])->where(['id' => $id])->get();
  }
  public function store(Request $request)
  {
    $this->validate($request, [
      'title' => 'required',
      'subtitle' => 'required',
      'content' => 'required'
    ], [
      'title.required' => 'Campo título é obrigatório',
      'subtitle.required' => 'Campo subtítulo é obrigatório',
      'content.required' => 'Campo conteúdo é obrigatório',
    ]);

    $post = new Post();
    $post->title = $request->title;
    $post->subtitle = $request->subtitle;
    $post->content = $request->content;

    if ($this->user->posts()->save($post))
      return response()->json([
        'data' => $post,
      ], 201);
    else
      return response()->json([
        'message' => 'Falha ao criar post.'
      ], 500);
  }
  public function update(Request $request, $id)
  {
    $post = $this->user->posts()->find($id);

    if (!$post) {
      return response()->json([
        'message' => 'Post não encontrado.'
      ], 400);
    }

    $updated = $post->fill($request->all())
      ->save();

    if ($updated) {
      return response()->json([
        'data' => $post
      ], 200);
    } else {
      return response()->json([
        'message' => 'Falha ao atualizar post.'
      ], 500);
    }
  }
  public function destroy($id)
  {
    $post = $this->user->posts()->find($id);

    if (!$post) {
      return response()->json([
        'message' => 'Post não encontrado.'
      ], 400);
    }

    if ($post->delete()) {
      return response()->json([
      ], 200);
    } else {
      return response()->json([
        'message' => 'Falha ao deletar post'
      ], 500);
    }
  }
  public function like(Request $request, $id)
  {
    $post = UserPost::updateOrCreate(['post_id' => $id, 'user_id' => $this->user->id], ['like' => $request->input('like')]);
    return $post;
  }
}
