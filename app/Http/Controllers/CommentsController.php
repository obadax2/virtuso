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
    \Log::info('Incoming request data:', $request->all());
    $validatedData = $request->validate([
        'comment' => 'required|string|max:500',  
        'post_id' => 'required|exists:posts,id', 
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
    return response()->json(['message' => 'Comment added successfully.','valid'=>1], 201);
}


    /**
     * Display the specified resource.
     */
   public function show($postId)
{
    $comments = Comments::where('post_id', $postId)
    ->with('user') 
    ->get()
    ->map(function ($comment) {
        return [
            'id' => $comment->id,
            'comment' => $comment->comment, 
            'username' => $comment->user->name, 
        ];
    });

return response()->json([
    'comments' => $comments,
]);
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
