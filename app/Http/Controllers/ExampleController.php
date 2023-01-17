<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function homepage(){
        $animals = ['frog', 'rabbit', 'hippo'];
        return view('homepage', ['animals' => $animals]);
    }

    public function aboutPage(){
        return view('single-post');
    }
}
