<?php

namespace App\Http\Controllers\api;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = auth()->user();
        $posts = Post::where('user_id', $authUser->id)->where('is_delete' , false)
                    ->orderBy('pinned', 'desc')
                    ->get();
        return response()->json([
            'message' => 'Successfully',
            'data' => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'cover_image' => 'required',
            'pinned' => 'boolean',
            'tag_id' => 'required|exists:tags,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $post = new Post();
        $post->title = $validated['title'];
        $post->body = $validated['body'];
        $post->pinned = $validated['pinned'] ?? false;
        $post->tag_id = $validated['tag_id'];
        $post->user_id = $validated['user_id'];
        $post->cover_image = $validated['cover_image'];

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/images', $filename);
            $post->cover_image = $filename;
        }

        $post->save();
        $tag = Tag::findOrFail($validated['tag_id']);
        $post->tags()->attach($tag);

        return response()->json([
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'error' => 'Post not found'
            ], 404);
        }

        return response()->json([
            'data' => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function restore(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'error' => 'Post not found'
            ], 404);
        }

        $post->is_delete  = false;
        $post->save();

        return response()->json([
            'message' => 'Post restore successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'error' => 'Post not found'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|max:255',
            'body' => 'sometimes|required',
            'cover_image' => 'sometimes|nullable|mimes:jpg,jpeg,png|max:2048',
            'pinned' => 'sometimes|boolean',
            'tag_id' => 'sometimes|required|exists:tags,id',
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        $post->fill($validated);



        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/images', $filename);
            $post->cover_image = $filename;
        }

        $post->save();

        return response()->json([
            'message' => 'Post updated successfully',
            'data' => $post
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'error' => 'Post not found'
            ], 404);
        }

        $post->is_delete  = true;
        $post->save();

        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }

    public function deletedPost()
{
    $authUser = auth()->user();

    $post = Post::where('user_id' , $authUser->id)->where('is_delete' ,true)->get();
    return response()->json([
        'data' => $post
    ]);
}

 }
