<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function storeAvatar(Request $request){
        //$request->file('avatar')->store('public/avatars');

        $request->validate([
            'avatar'    =>  'required|image|max:3000'
        ]);

        //$request->file('avatar')->store('public/avatars');

        $user = auth()->user();

        $filename = $user->id . '-' . uniqid() . '.jpg';

        //Run the command 'composer require intervention/image' for using Image (image resizing capabilities) -- count cipher
        $imgData = Image::make($request->file('avatar'))->fit(120)->encode('jpg');
        Storage::put('public/avatars/' . $filename, $imgData);

        $oldAvatar = $user->avatar;
        
        $user->avatar = $filename;
        $user->save();

        if($oldAvatar != "/fallback-avatar.jpg"){
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));
        }

        return back()->with('success', 'Avatar Updated');
        
    }

    public function showAvatarForm(){
        return view('avatar-form');
    }

    public function profile(User $user){
        $currentlyFollowing = 0;

        if(auth()->check()){
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }
       

        //return $user->posts()->get(); //This method is set up in the User model; creates relationship between User and Post
        
        return view('profile-posts', [
            'user' =>  $user, //Passing in data that will be converted to a variable for view this is going to
            'posts' =>  $user->posts()->latest()->get(),
            'username'  =>  $user->username,
            'postCount' =>  $user->posts()->count(),
            'avatar'    =>  $user->avatar,
            'currentlyFollowing'    =>  $currentlyFollowing
        ]);
    }

    public function logout(){
        auth()->logout();
        return redirect('/')->with('success', 'You are now logged out');
    }

    public function showCorrectHomepage(){
        if(auth()->check()){
            return view('homepage-feed');
        }else{
            return view('homepage');
        }
    }

    public function login(Request $request){
        $incomingFields = $request->validate([
            'loginusername' =>  'required',
            'loginpassword' =>  'required'
        ]);

        if(auth()->attempt([
            'username' => $incomingFields['loginusername'],
            'password' => $incomingFields['loginpassword'],
            ])){
                $request->session()->regenerate();
                return redirect('/')->with('success', 'You are logged in');
        }else{
            return redirect('/')->with('failure', 'Invalid login');
        }
    }


    public function register(Request $request){
        $incomingFields = $request->validate([
            'username'  =>  ['required', 'min:3', 'max:30', Rule::unique('users', 'username')],
            'email'  =>  ['required', 'email', Rule::unique('users', 'email')],
            'password'  =>  ['required', 'min:8', 'confirmed']
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);

        $user = User::create($incomingFields);

        auth()->login($user);
        
        return redirect('/')->with('success', 'Thank you for creating an account');
    }

}
