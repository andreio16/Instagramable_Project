<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class AdminController extends Controller
{
    public function index()
    {
        
        $posts = Post::all();

        return view('home', [
            'posts' => $posts
        ]);
    }

    public function view($id)
    {   
        $post = Post::findOrFail($id);
        
        return view('postView', [
            'post' => $post
        ]);
    }

    public function viewComments($id)
    {   
        # code...
    }
}
