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
    
}