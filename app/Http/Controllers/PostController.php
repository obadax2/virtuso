<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }





    public function store(Request $request)
    {
        Log::info('Request data: ', $request->all());
        $validatedData = $request->validate([
            'userId' => 'required|integer|exists:users,id',
            'desc' => 'required|string|max:300',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,bmp,webp,tiff,tif,heic,heif|max:8126',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:10240',
        ]);

        try {

            $post = new Post();
            $post->post_desc = $validatedData['desc'];

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $post->photo = $imagePath;
            }


            if ($request->hasFile('audio')) {
                $audioPath = $request->file('audio')->store('audios', 'public');
                $post->music = $audioPath;
            }


            $post->user_id = $validatedData['userId'];

            $post->save();

            return response()->json(['valid' => 1]);

        } catch (\Exception $e) {
            Log::error("Error creating post: " . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
   public function show(Request $request)
{
    Log::info('DisplayPost request data: ', $request->all());

    $validatedData = $request->validate([
        'userId' => 'required|integer|exists:users,id',
    ]);

    try {
        $user = User::findOrFail($validatedData['userId']);

        // Fetch posts with user and comments relationships
        $userPosts = Post::with(['user', 'comments', 'comments.subComments'])
            ->where('user_id', $user->id)
            ->withCount('likes') // Get count of likes
            ->get();

        Log::info('Fetched user posts:', $userPosts->toArray());

        $formattedPosts = $userPosts->map(function ($post) {
            return [
                'id' => $post->id,
                'name' => $post->user ? $post->user->name : 'Unknown User',
                'image' => $post->photo ? url('storage/' . $post->photo) : null,
                'audio' => $post->music ? url('storage/' . $post->music) : null,
                'likes_count' => $post->likes_count, // Uses the likes count fetched earlier
                'comments' => $post->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'user_id' => $comment->users_id,
                        'comment' => $comment->comment,
                        'likes_comment'=>$comment->likes,
                        'sub_comments' => $comment->subComments->map(function ($subComment) {
                            return [
                                'id' => $subComment->id,
                                'user_id' => $subComment->users_id,
                                'comment' => $subComment->sub_comment,
                                'likes_subComment'=>$subComment->likes,
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json([
            'valid' => 1,
            'posts' => $formattedPosts,
        ]);
    } catch (\Exception $e) {
        Log::error('Error fetching posts: ', ['error' => $e->getMessage(), 'trace' => $e->getTrace()]);
        return response()->json(['error' => 'Failed to fetch posts'], 500);
    }
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
