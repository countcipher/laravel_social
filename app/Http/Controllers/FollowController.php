<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $user){
        // you cannot follow yourself
        if($user->id == auth()->user()->id){
            return back()->with('failure', 'You cannot follow yourself');
        }

        // you cannot follow someone you're already following
        $existCheck = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->username]])->count();

        if($existCheck){
            return back()->with('failure', 'You are already following that user');

        }

        return back()->with('success', 'User successfully followed');

        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();
    }

    public function removeFollow(User $user){
        Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->delete();

        return back()->with('success', 'User successfully unfollowed');
    }
}
