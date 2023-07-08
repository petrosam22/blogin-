<?php

namespace App\Http\Controllers\api;
use App\Models\User;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public  function stats(){
        $users = User::all()->count();
        $posts = Post::all()->count();
        $usersWithNoPost = User::withCount('posts')->having('posts_count', '=', 0)->get()->count();


        return response()->json([
            'users' => $users,
            'posts' => $posts,
            'usersNoPost' => $usersWithNoPost
        ]);


    }
}
