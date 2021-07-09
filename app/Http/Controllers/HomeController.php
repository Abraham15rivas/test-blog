<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $events = Post::where('published', '=', true)
                        ->get();
        return $events->toJson();
    }

    public function show(Post $pots, Request $request) {
        if($request->ajax()) {
            return $pots;
        }
        $check = \Auth::check();
        if(isset($request->guest)) {
            return view('index', compact('post', 'check'));
        }
        return view('welcome', compact('check'));
    }
}
