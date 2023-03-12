<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Follower;

class FollowerController extends Controller
{
    public function follow($id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json(['success' => false, 'message' => 'User not found'], 201);
        }
        $follower = auth()->user();
        $follow = new Follower;

        $check  = $follow->where('user_id', $user->id)->where('follower_id', $follower->id)->first();
        if($check) {
            return response()->json(['success' => false, 'message' => 'You are already following this user'], 201);
        }
                
        $follow->user_id = $user->id;
        $follow->follower_id = $follower->id;
        $follow->save();
        
        return response()->json(['success' => true, 'message' => 'You are now following '.$user->name], 201);
    }

    public function unfollow($id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json(['success' => false, 'message' => 'User not found'], 201);
        }
        $follower = auth()->user();
        $follow = new Follower;
        $check  = $follow->where('user_id', $user->id)->where('follower_id', $follower->id)->first();
        if($check) {
            $check->delete();
        }
        return response()->json(['success' => true, 'message' => 'You just unfollowed '.$user->name], 201);
    }

}
