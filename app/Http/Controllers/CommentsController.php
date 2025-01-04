<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comments;
use App\Models\Post;
use App\Models\User;



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
    $comment->likes = 0;

    $comment->save(); // Save the comment to the database

    // Return a success message
    return response()->json(['message' => 'Comment added successfully.','valid'=>1], 201);
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
    // Validate the incoming request
    $validatedData = $request->validate([
        'comment' => 'required|string|max:255', // Validate the comment content
        'user_id' => 'required|integer',       // Ensure the user_id is provided
    ]);

    // Find the comment by ID
    $comment = Comments::find($id);

    // If the comment doesn't exist, return an error
    if (!$comment) {
        return response()->json(['message' => 'Comment not found.'], 404);
    }

    // Check if the user ID matches the comment's user_id
    if ($comment->users_id != $validatedData['user_id']) {
        return response()->json(['message' => 'You are not authorized to update this comment.'], 403);
    }

    // Update the comment content
    $comment->comment = $validatedData['comment'];
    $comment->save();

    // Return the updated comment
    return response()->json([
        'message' => 'Comment updated successfully.',
        'comment' => $comment,
    ], 200);
}


    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Request $request, string $id)
{
    // Validate the incoming request to ensure user_id is present
    $validatedData = $request->validate([
        'user_id' => 'required|integer', // Ensure the user_id is provided
    ]);

    // Find the comment by ID
    $comment = Comments::find($id);

    // If the comment doesn't exist, return an error
    if (!$comment) {
        return response()->json(['message' => 'Comment not found.'], 404);
    }

    // Check if the user owns the comment
    if ($comment->users_id !== $validatedData['user_id']) {
        return response()->json(['message' => 'You are not authorized to delete this comment.'], 403);
    }

    // Delete the comment
    $comment->delete();

    // Return a success response
    return response()->json(['message' => 'Comment deleted successfully.'], 200);
}




public function updateLikes(Request $request, $id)
{

    // Validate the incoming request
    $validatedData = $request->validate([
        'like' => 'required|boolean', // Accepts 0 or 1 as valid values
    ]);

    // Find the comment by ID
    $comment = Comments::find($id);

    // If the comment doesn't exist, return an error
    if (!$comment) {
        return response()->json(['message' => 'Comment not found.'], 404);
    }

    // Update the like count based on the request
    if ($validatedData['like'] == 1) {
        $comment->increment('likes'); // Increment the likes column
    } else {
        $comment->decrement('likes'); // Decrement the likes column
    }

    // Return the updated comment with its likes count
    return response()->json([
        'message' => 'Comment likes updated successfully.',
        'comment_id' => $comment->id,
        'likes' => $comment->likes,
    ], 200);
}



}
