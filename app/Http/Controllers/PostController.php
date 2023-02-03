<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function search($term){
        $posts = Post::search($term)->get();
        $posts->load('user:id,username,avatar');
        return $posts;
    }

    public function actuallyUpdate(Post $post, Request $request){
        $incomingFields = $request->validate([
            'title' =>  'required',
            'body'  =>  'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);

        return back()->with('success', 'Post updated');
    }   

    public function showEditForm(Post $post){
        return view('edit-post', [
            'post'  =>  $post
        ]);
    }

    public function delete(Post $post){
    //    if(auth()->user()->cannot('delete', $post)){
    //         return "you cannot do that";
    //    }
       $post->delete();

       return redirect('/profile/' . auth()->user()->username)->with('success', 'Post successfully deleted');
    }

    public function viewSinglePost(POST $post){
        
        $post['body'] = strip_tags(Str::markdown($post->body), '<p><ul><ol><li><strong><em><a>');

        return view('single-post', [
            'post'  =>  $post
        ]);

        /**
         * To get the information of the user that posted
         * the post, create the realtionship in the Post.php model
         */
    }

    public function storeNewPost(Request $request){
        $incomingFields = $request->validate([
            'title' =>  'required',
            'body'  =>  'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id(); //Gets user id from User model

        $newPost = Post::create($incomingFields);

        return redirect("/post/{$newPost->id}")->with('success', 'New post successfully created');
    }

    public function showCreateForm(){
        return view('create-post');
    }
}
