<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function viewSinglePost(POST $post){
        return view('single-post', [
            'post'  =>  $post
        ]);
    }

    public function storeNewPost(Request $request){
        $incomingFields = $request->validate([
            'title' =>  'required',
            'body'  =>  'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id(); //Gets user id from user model

        Post::create($incomingFields);
    }

    public function showCreateForm(){
        return view('create-post');
    }
}
