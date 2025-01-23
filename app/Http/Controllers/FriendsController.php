<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Friends;
class FriendsController extends Controller
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
        $validatedData = $request->validate([
            'sender_id' => 'required|exists:users,id|integer',
            'reciver_id' => 'required|exists:users,id|integer',
        ]);
    
        if ($validatedData['sender_id'] === $validatedData['reciver_id']) {
            return response()->json(['success' => false, 'message' => 'You cannot send a friend request to yourself.']);
        }
    
        $existingRequest = Friends::where('user_id', $validatedData['sender_id'])
            ->where('friend_id', $validatedData['reciver_id'])
            ->first();
    
        if ($existingRequest) {
            return response()->json(['success' => false, 'message' => 'A friend request has already been sent.']);
        }
    
        $friendRequest = new Friends();
        $friendRequest->user_id = $validatedData['sender_id'];
        $friendRequest->friend_id = $validatedData['reciver_id'];
        $friendRequest->status = 'pending';
        $friendRequest->save();
    
        return response()->json(['success' => true, 'message' => 'Friend request sent successfully!']);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'text' => 'required|string', 
            'user_id' => 'required|exists:users,id', 
        ]);
        
        // Log the validated input data
        \Log::info('Validated data: ', $validatedData);

        $searchText = $validatedData['text'];
        $userId = $validatedData['user_id'];
        \Log::info('Searching for users with text: ' . $searchText . ' and user_id: ' . $userId);
        
        // Query the User model
        try {
            $friends = User::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchText) . '%'])
                ->where('id', '<>', $userId) 
                ->get();
        } catch (\Exception $e) {
            // Log any exceptions that occur during the query
            \Log::error('Error during querying users: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during user search.',
                'data' => []
            ]);
        }

        // Check if any friends were found
        if ($friends->isEmpty()) {
            \Log::info('No users found for search text: ' . $searchText);
            return response()->json([
                'success' => false,
                'message' => 'No users found.',
                'data' => []
            ]);
        }
        
        // Log the found users
        \Log::info('Users found: ', $friends->toArray());
    
        // Return the found users
        return response()->json([
            'success' => true,
            'message' => 'Users found.',
            'data' => $friends
        ]);
    
    }

    /**
     * Display the specified resource.
     */public function show(Request $request)
{
    $userId = $request->query('userId');

    if (!$userId) {
        return response()->json(['valid' => 0, 'message' => 'User ID is required.'], 400);
    }

    $friendRequests = Friends::where('friend_id', $userId)
        ->where('status', 'pending')
        ->with(['sender' => function ($query) {
            $query->select('id', 'name'); 
        }]) 
        ->get();

    return response()->json([
        'valid' => 1,
        'requests' => $friendRequests->map(function ($friendRequest) {
            
            return [
                'id' => $friendRequest->id,
                'sender_name' => $friendRequest->sender->name ?? 'Unknown',     
            ];
        })->all(),
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
    public function update(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|exists:friends,id',
            'user_id' => 'required|exists:users,id',
            'accepted' => 'required|boolean',
        ]);

        $friendRequest = Friends::find($validated['request_id']);

        // Check if the user_id matches the friend's ID
        if ($friendRequest->friend_id !== $validated['user_id']) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        \Log::info('Status being set:', ['status' => $request->input('status')]);
        $friendRequest->status = $validated['accepted'] ? 'approved' : 'rejected';
        $friendRequest->save();

        return response()->json(['success' => true, 'message' => 'Friend request updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
