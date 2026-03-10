<?php

namespace Acme\Post\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * PostController
 *
 * Handles all CRUD operations for the Post custom Nova tool.
 * Every method shows which Nova field type is being handled.
 */
class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();
        return response()->json($posts);
    }

    public function store(Request $request){

    // !! validate all fields
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'author'           => 'nullable|string|max:255',
            'meta_title'       => 'nullable|string|max:255',
            'slug'             => 'required|string|max:255|unique:posts,slug',
            'content'          => 'nullable|string',
            'excerpt'          => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',
            'status'           => 'required|in:draft,published,archived',
            'category'         => 'nullable|string|max:100',
            'is_featured'      => 'boolean',
            'views'            => 'nullable|integer|min:0',
            'rating'           => 'nullable|numeric|min:0|max:10',
            'reading_time'     => 'nullable|integer|min:1',
            'published_at'     => 'nullable|date',
            'featured_image'   => 'nullable|string|max:2048',
            'tags'             => 'nullable|string|max:500',
        ]);

        $post = Post::create($validated);

        return response()->json($post, 201);
    }

  
    public function show($id)
    {
        $post = Post::findOrFail($id);

        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'author'           => 'nullable|string|max:255',
            'meta_title'       => 'nullable|string|max:255',
            'slug'             => 'required|string|max:255|unique:posts,slug,' . $id,
            'content'          => 'nullable|string',
            'excerpt'          => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',
            'status'           => 'required|in:draft,published,archived',
            'category'         => 'nullable|string|max:100',
            'is_featured'      => 'boolean',
            'views'            => 'nullable|integer|min:0',
            'rating'           => 'nullable|numeric|min:0|max:10',
            'reading_time'     => 'nullable|integer|min:1',
            'published_at'     => 'nullable|date',
            'featured_image'   => 'nullable|string|max:2048',
            'tags'             => 'nullable|string|max:500',
        ]);

        $post->update($validated);

        return response()->json($post);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully.']);
    }
}
