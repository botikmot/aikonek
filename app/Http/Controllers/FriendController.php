<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FriendRequest;
use App\Models\Friend;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FriendController extends Controller
{
    public function requestFriend(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer'
        ]);

        $friendRequest = new FriendRequest();
        $friendRequest->friend_id = Auth::id();
        $friendRequest->user_id = $validatedData['user_id'];
        $friendRequest->save();

        return response()->json(['success' => true, 'message' => 'Friend request sent!'], 201);
    }

    public function acceptFriend(Request $friendRequest)
    {   
        $friend = new Friend();
        $friend->user_id = Auth::id();
        $friend->friend_id = $friendRequest->friend_id;
        $friend->save();

        $friend2 = new Friend();
        $friend2->user_id = $friendRequest->friend_id;
        $friend2->friend_id = Auth::id();
        $friend2->save();


        $request = FriendRequest::findOrFail($friendRequest->id);
        $request->delete();

        return response()->json(['success' => true, 'message' => 'Friend request accepted!'], 201);
    }

    public function unFriend(Request $request)
    {
        $user_id = Auth::id();
        $unfriend = Friend::where('user_id', $user_id)->where('friend_id', $request->user_id)->first();

        $unfriend2 = new Friend();
        $unfriend2 = $unfriend2->where('user_id', $request->user_id)->where('friend_id',  $user_id)->first();

        if($unfriend){
            $unfriend->delete();
            if($unfriend2){
                $unfriend2->delete();
            }
            return response()->json(['success' => true, 'message' => 'Successfully unfriend a friend.'], 201);
        }

        return response()->json(['success' => false, 'message' => 'User not found'], 201);
    }

    public function getFriends()
    {
        $user = Auth::user();
        $friends = $user->friends;
        return response()->json(['success' => false, 'friends' => $friends], 201);
    }

    public function suggestedFriends()
    {
        $user = Auth::user();
        $allUsers = User::where('id', '!=', $user->id)->get();
        $friends = $user->friends;
        $suggestedFriends = $allUsers->diff($friends);
        if($suggestedFriends->count() < 5) {
            $suggestedFriends = $suggestedFriends->random($suggestedFriends->count());
        } else {
            $suggestedFriends = $suggestedFriends->random(5);
        }    
        
        foreach ($suggestedFriends as $friend) {
            $friend_request = new FriendRequest();
            $friend_request_sent = $friend_request->where('user_id', $user->id)->where('friend_id', $friend->id)->first();
            $friend_request_received = $friend_request->where('friend_id', $user->id)->where('user_id', $friend->id)->first();
            $friend->friend_request_sent = $friend_request_sent ? true : false;
            $friend->friend_request_received = $friend_request_received ? true : false;
            if($friend_request_sent){
                $friend->friend_request_sent_id = $friend_request_sent->id;
            }
        }

        return response()->json(['success' => true, 'suggestedfriends' => $suggestedFriends], 201);
    }

}
