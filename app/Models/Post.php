<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use Searchable;
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'user_id'
    ];

    //For search functionality, this function must be named this --count cipher
    public function toSearchableArray(){
        return [
            'title' =>  $this->title,
            'body'  =>  $this->body
        ];
    }

    //Name of method doesn't have to be same as the model name.
    //This method creates relationship between Post and User (joining tables)
    //The name of the function becomes the name of the variable
    //to use to get the dynamic content.
    //See 'single-post.blade.php' for using $post->user-username --count cipher
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
