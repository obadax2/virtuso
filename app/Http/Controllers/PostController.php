<?php

namespace App\Http\Controllers;


use App\Models\Post;

use Illuminate\Http\Request;

class PostController extends Controller
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
    public function create(Request $request)
    {
        //
        $validatedData = $request->validate([
            'userId' => 'required|integer|exists:users,id', 
            'desc' => 'required|string|max:300',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8126',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
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
            \Log::error("Error creating post: " . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


    public function store(Request $request)
    {
        //
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
