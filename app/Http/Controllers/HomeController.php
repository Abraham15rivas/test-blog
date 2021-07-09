<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Post, Comment};

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $posts = Post::where('published', '=', true)->orderByDesc('created_at')->get();
        return $posts->toJson();
    }

    public function show(Post $post, Request $request) {
        if($request->ajax()) {
            return $post;
        }

        $check = \Auth::check();

        if(isset($request->guest)) {
            return view('index', compact('post', 'check'));
        }

        return view('welcome', compact('check'));
    }

    public function allApproved(Post $post) {
        $post->load(['comments.answer', 'comments' => function($query) {
            return $query->where('status', '=', 'approved')->get();
        }]);

        return $post->comments;
    }

    public function setComment(Request $request) {
        $check = \Auth::check();

        if($check) {
            $user = auth()->user();
            $request->participant_data = [
                'email'     => $user->email,
                'user_id'   => $user->id
            ];
        }

        Comment::create([
            'content'           => "¿ $request->content ?",
            'participant_data'  => json_encode($request->participant_data),
            'post_id'           => $request->post_id
        ]);

        return 'ok';
    }

    public function countVisit(Request $request) {
        $post = Post::find($request->id);
        $post->update([
            'visits' => $post->visits + 1
        ]);
    }
}
