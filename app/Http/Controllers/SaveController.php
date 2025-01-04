<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Save;
use App\Models\User;
use App\Models\Post;




class SaveController extends Controller
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
        'post_id' => 'required|exists:posts,id', // Validate that the post exists
        'user_id' => 'required|exists:users,id', // Validate that the user exists
    ]);

    $postId = $validatedData['post_id'];
    $userId = $validatedData['user_id'];

    // Check if the save entry already exists
    $existingSave = Save::where(column: 'post_id', operator: $postId)
        ->where(column: 'user_id', operator: $userId)
        ->first();

    if (!$existingSave) {
        // Create and save the new save entry
        $save = new Save();
        $save->post_id = $postId;
        $save->user_id = $userId;
        //  return $existingSave;
        $save->save();

        return response()->json(['message' => 'Post saved successfully.', 'valid' => 1], 201);
    }

    // If already saved, return a message
    return response()->json(['message' => 'You already saved this post.'], 200);
    }

    /**
     * Display the specified resource.
     */
   public function show(string $id)
{

    // Get the user by id
    $user = User::find($id);
    // If the user doesn't exist, return an error message
    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    // Get all the posts that the user has saved
    $savedPosts = Post::whereHas('saves', function ($query) use ($id) {
        $query->where('user_id', $id); // Check if a save record exists with the user_id
    })->get();

    // Return the saved posts
    return response()->json([
        'user_id' => $id,
        'saved_posts' => $savedPosts
    ], 200);
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
