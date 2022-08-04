<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;

class PostController extends Controller
{
  public function index() 
  {
    return view('posts.index', [
          'posts' => Post::latest()->paginate()
      ]);
  }

  public function create(Post $post) 
  {

    return view('posts.create',['post' => $post]);
  }

  public function store(Request $request) 
  {
    $request->validate([
      'title' => 'required|max:255',
      'slug' => 'required|max:255|unique:posts,slug',
      'body' => 'required|max:255',
    ]);

    $post = $request->user()->posts()->create([
        'title' => $title = $request->title,
        'slug'  => Str::slug($title),
        'body'  => $request->body,
    ]);

    return redirect()->route('posts.edit', $post);
  }

  public function edit(Post $post) 
  {
    return view('posts.edit',['post' => $post]);
  }

  public function update(Request $request, Post $post)
  {
    $request->validate([
      'title' => 'required|max:255',
      'slug' => 'required|max:255|unique:posts,slug' . $post->id,
      'body' => 'required|max:255',
    ]);

    $post->update([
        'title' => $title = $request->title,
        'slug'  => Str::slug($title),
        'body'  => $request->body,
    ]);

    return redirect()->route('posts.edit', $post);
  }
  
  public function destroy(Post $post) 
  {
    $post->delete();
    return back();
  }
}
