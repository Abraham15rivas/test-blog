<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PostRequest;
use App\Models\Post;

class HomeController extends Controller
{
    public function index() {
        $posts = Post::get();
        return $posts->toJson();
    }

    public function store(PostRequest $request) {
        $user = auth()->user();
        
        $post               = new Post();
        $post->title        = $request->title;
        $post->author       = $request->author;
        $post->slug         = $request->slug;
        $post->description  = $request->description;
        $post->video        = $request->video;
        $post->comment      = $request->comment == 'true' ? 1 : 0;
        $post->published    = $request->published == 'true' ? 1 : 0;
        $post->user_id      = $user->id;

        if($request->hasFile('image')) {
            $path           = $request->file('image')->store('public/img/posts');
            $post->image    = explode('public/', $path)[1];
        }

        $post->save();

        return 'ok';
    }

    public function update(Post $post, PostRequest $request) {
        $user = auth()->user();

        if($request->hasFile('image')) {
            if($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path           = $request->file('image')->store('public/img/posts');
            $request->image = explode('public/', $path)[1];
        }

        $post->update([
            'title'         => $request->title,
            'author'        => $request->author,
            'slug'          => $request->slug,
            'description'   => $request->description,
            'image'         => $request->image,
            'video'         => $request->video,
            'comment'       => $request->comment == 'true' ? 1 : 0,
            'published'     => $request->published == 'true' ? 1 : 0,
            'user_id'       => $user->id
        ]);

        return 'ok';
    }

    public function destroy(Post $post) {
        if($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return 'ok';
    }
}
