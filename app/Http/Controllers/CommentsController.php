<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comments;
use App\Models\Post;


class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'comment' => 'required|string|max:500',  // Ensure the comment is a string and not too long
        'post_id' => 'required|exists:posts,id', // Validate that the post exists
        'user_id' => 'required|exists:users,id', // Validate that the user exists
    ]);

    $commentText = $validatedData['comment'];
    $postId = $validatedData['post_id'];
    $userId = $validatedData['user_id'];

    // Create a new Comment instance and save it to the database
    $comment = new Comments();

    $comment->comment = $commentText; // Set the comment text
    $comment->post_id = $postId; // Associate with the correct post
    $comment->users_id = $userId; // Associate with the correct user
    $comment->likes = 1;

    $comment->save(); // Save the comment to the database

    // Return a success message
    return response()->json(['message' => 'Comment added successfully.'], 201);
}


    /**
     * Display the specified resource.
     */
   public function show(string $id)
{
    // Validate that the post exists (you can return a 404 if the post doesn't exist)
    $post = Post::find(id: $id);

    if (!$post) {
        return response()->json(['message' => 'Post not found.'], 404);
    }

    // Get all comments associated with the post
    $comments = Comments::where('post_id', operator: $id)->get();

    // Return the comments as a JSON response
    return response()->json([
        'post_id' => $id,
        'comments' => $comments
    ], status: 200);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
