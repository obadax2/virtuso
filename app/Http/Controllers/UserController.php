<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{

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

    


}
