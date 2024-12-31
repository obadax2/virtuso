<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;







class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 1;
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
    // Validate the request data
    $validatedData = $request->validate([
        'like' => 'required|boolean', // Ensures the value is 0 or 1
        'post_id' => 'required|exists:posts,id', // Validates the post ID exists
        'user_id' => 'required|exists:users,id', // Validates the user ID exists
    ]);

    $likeValue = $validatedData['like'];
    $postId = $validatedData['post_id'];
    $userId = $validatedData['user_id'];

    // If like value is 1, add the like
    if ($likeValue == 1) {
        // Check if the like already exists
        $existingLike = Like::where('post_id', $postId)
            ->where('user_id', $userId)
            ->first();

        if (!$existingLike) {
            // Create and save the new like
            $like = new Like();
            $like->post_id = $postId;
            $like->user_id = $userId;
            $like->save();

            return response()->json(['message' => 'Like added successfully.'], 201);
        }

        return response()->json(['message' => 'You already liked this post.'], 200);
    }

    // If like value is 0, remove the like
    if ($likeValue == 0) {
        // Find the like and delete it
        $like = Like::where('post_id', $postId)
            ->where('user_id', $userId)
            ->first();

        if ($like) {
            $like->delete();

            return response()->json(['message' => 'Like removed successfully.'], 200);
        }

        return response()->json(['message' => 'Like not found.'], 404);
    }
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

    // Get all the posts that the user has liked
    $likedPosts = Post::whereHas('likes', function ($query) use ($id) {
        $query->where('user_id', $id);  // Check if a like record exists with the user_id
    })->get();

    // Return the liked posts
    return response()->json([
        'user_id' => $id,
        'liked_posts' => $likedPosts
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
