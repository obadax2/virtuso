<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Favourit;
class FavouritsController extends Controller
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
    // Validate the request data
    $validatedData = $request->validate([
        'post_id' => 'required|exists:posts,id', // Validate that the post exists
        'user_id' => 'required|exists:users,id', // Validate that the user exists
    ]);

    $postId = $validatedData['post_id'];
    $userId = $validatedData['user_id'];

    // Check if the favorite entry already exists
    $existingFavorite = Favourit::where('post_id', $postId)
        ->where('user_id', $userId)
        ->first();

    if (!$existingFavorite) {
        // Create and save the new favorite entry
        $favorite = new Favourit();
        $favorite->post_id = $postId;
        $favorite->user_id = $userId;
        $favorite->save();

        return response()->json(['message' => 'Post favorited successfully.', 'valid' => 1], 201);
    }

    // If already favorited, return a message
    return response()->json(['message' => 'You already favorited this post.'], 200);
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
