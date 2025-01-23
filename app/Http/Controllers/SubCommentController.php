<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubComments;

class SubCommentController extends Controller
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
        // Validate the incoming request
    $validatedData = $request->validate([
        'sub_comment' => 'required|string|max:500',  // Ensure the sub-comment is a string and not too long
        'comment_id' => 'required|exists:comments,id', // Validate that the comment exists
        'user_id' => 'required|exists:users,id',      // Validate that the user exists
    ]);

    $subCommentText = $validatedData['sub_comment'];
    $commentId = $validatedData['comment_id'];
    $userId = $validatedData['user_id'];

    // Create a new SubComment instance and save it to the database
    $subComment = new SubComments();

    $subComment->sub_comment = $subCommentText; // Set the sub-comment text
    $subComment->comment_id = $commentId;       // Associate with the correct comment
    $subComment->users_id = $userId;            // Associate with the correct user
    $subComment->likes = 0;                     // Default likes to 0

    $subComment->save(); // Save the sub-comment to the database

    // Return a success message
    return response()->json(['message' => 'Sub-comment added successfully.', 'valid' => 1], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        'SubComment' => 'required|string|max:255', // Validate the comment content
        'user_id' => 'required|integer',       // Ensure the user_id is provided
    ]);

    // Find the comment by ID
    $subComment = SubComments::find($id);

    // If the comment doesn't exist, return an error
    if (!$subComment) {
        return response()->json(['message' => 'Comment not found.'], 404);
    }

    // Check if the user ID matches the comment's user_id
    if ($subComment->users_id != $validatedData['user_id']) {
        return response()->json(['message' => 'You are not authorized to update this comment.'], 403);
    }

    // Update the comment content
    $subComment->sub_comment = $validatedData['SubComment'];
    $subComment->save();

    // Return the updated comment
    return response()->json([
        'message' => 'SubComment updated successfully.',
        'SubComment' => $subComment,
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
    $subComment = SubComments::find($id);

    // If the comment doesn't exist, return an error
    if (!$subComment) {
        return response()->json(['message' => 'Comment not found.'], 404);
    }

    // Check if the user owns the comment
    if ($subComment->users_id !== $validatedData['user_id']) {
        return response()->json(['message' => 'You are not authorized to delete this comment.'], 403);
    }

    // Delete the comment
    $subComment->delete();

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
    $SubComment = SubComments::find($id);

    // If the comment doesn't exist, return an error
    if (!$SubComment) {
        return response()->json(['message' => 'Comment not found.'], 404);
    }

    // Update the like count based on the request
    if ($validatedData['like'] == 1) {
        $SubComment->increment('likes'); // Increment the likes column
    } else {
        $SubComment->decrement('likes'); // Decrement the likes column
    }

    // Return the updated comment with its likes count
    return response()->json([
        'message' => 'Comment likes updated successfully.',
        'subComment_id' => $SubComment->id,
        'likes' => $SubComment->likes,
    ], 200);
}
}
