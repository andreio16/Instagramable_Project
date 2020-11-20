<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use DB;

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
        $post = Post::findOrFail($id);
        $comments = DB::table('comments')->where('post_id', '=', $post->id)->pluck('text');
        $usersWhoComment = DB::table('comments')->where('post_id', '=', $post->id)->pluck('user_id');
    
        $results = array();
        foreach($usersWhoComment as $userID)
        {
            $usernames = DB::table('users')->where('id', '=', $userID)->pluck('username');
            array_push($results, $usernames);
        }


        return view('commentView', [
            'post' => $post,
            'comments' => $comments,
            'usernames' => $results
        ]);
    }
}
