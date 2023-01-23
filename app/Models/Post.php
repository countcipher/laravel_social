<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'user_id'
    ];

    //Name of method doesn't have to be same as the model name.
    //This method creates relationship between Post and User (joining tables)
    //The name of the function becomes the name of the variable
    //to use to get the dynamic content.
    //See 'single-post.blade.php' for using $post->user-username --count cipher
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
