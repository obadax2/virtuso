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
