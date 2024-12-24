<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;  
class AuthController extends Controller
{
    

public function register(Request $request)
{
    $validatedData = $request->validate([
        'fullName' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    try {
        
        User::create([
            'name' => $validatedData['fullName'], 
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), 
            'profile_picture' => null, 
        ]);

        return response()->json(['valid' => 1]);
    } catch (\Exception $e) {
        
        \Log::error($e->getMessage());

        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}


public function login(Request $request)
{
    $validatedData = $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

   
    $user = User::where('email', $validatedData['email'])->first();


    if ($user && Hash::check($validatedData['password'], $user->password)) {
        return response()->json([
            'valid' => 1,
            'username' => $user->name, 
            'id' => $user->id,
            
        ]);
    }

    
    return response()->json([
        'valid' => 0,
        'error' => 'Invalid credentials'
    ], 401);
}
    public function ViewUser(Request $request) {
        $user = User::where('id', $request->id)->first();
    
        if (!$user) {
            return response()->json(['error' => 'User does not exist'], 404);
        }
    
        return response()->json([
            'id' => $user->id,
            'username' => $user->name,
            'email' => $user->email,
            'password'=>$user->password,
            'profile'=>$user->profile_picture
        ]);
    }
    public function profile(Request $request) {
       
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
            'user_id' => 'required|integer|exists:users,id', 
        ]);
    
        
        $user = User::find($request->user_id);
    
        if (!$user) {
            return response()->json(['error' => 'User does not exist'], 404);
        }
    
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            
            $url = asset('storage/' . $path);
            
            $user->profile_picture = $path; 
            $user->save();
        
            return response()->json(['message' => 'Profile picture set successfully', 'url' => $url], 200);
        }
    
        return response()->json(['message' => 'Failed to set profile picture'], 400);
    }
    public function checkpassword(Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'required|string|min:8',
            'id' => 'required|integer',
        ]);
        
        $user = User::where('id', $request->id)->first();
    
        if ($user && Hash::check($validatedData['password'], $user->password)) {
            return response()->json(['valid' => 1]);
        }
    
        return response()->json(['valid' => 0], 401); 
    }
    public function updatepassword(Request $request){
        $validatedData=$request->validate([
            'password' => 'required|string|min:8',
            'id'=>'required',

        ]);

        if($user = User::where('id', $request->id)->first()){

        
        $user->password=Hash::make($request->password);
        $user->save();
        return response()->json([ 'valid' => 1,]);}
        else {
            return response()->json([ 'valid' => 0,]);
        }
      
       



        



    }
    public function create(Request $request)
    {
        // Log incoming request data
        \Log::info('Request data: ', $request->all());
    
        $validatedData = $request->validate([
            'userId' => 'required|integer|exists:users,id',
            'desc' => 'required|string|max:300',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,bmp,webp,tiff,tif,heic,heif|max:8126',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:10240',
        ]);
    
        try {
            $post = new Post();
            $post->post_desc = $validatedData['desc'];
    
            // Image handling
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $post->photo = $imagePath; 
            } else {
                $post->photo = null;
            }
    
            // Audio handling
            if ($request->hasFile('audio')) {
                try {
                    $audioPath = $request->file('audio')->store('audios', 'public');
                    $post->music = $audioPath;
                } catch (\Exception $e) {
                    \Log::error("Audio upload failed: " . $e->getMessage());
                    return response()->json(['error' => 'Audio upload failed'], 500);
                }
            } else {
                \Log::info('No audio file was provided in the request.');
            }
    
            $post->user_id = $validatedData['userId'];
            $post->save();
    
            return response()->json(['valid' => 1]);
    
        } catch (\Exception $e) {
            \Log::error("Error creating post: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    
}