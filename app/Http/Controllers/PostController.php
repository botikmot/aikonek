<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Friend;
use App\Models\Like;
use DB;

class PostController extends Controller
{
    public function createPost(Request $request)
    {
        $validator = $request->validate([
            'content' => 'required|max:255',
            'privacy' => 'required',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,gif,svg,mp4,mkv|max:20048'
        ]);

        $post = new Post($validator);
        $post->user_id = Auth::user()->id;
        $post->save();

        if ($request->hasFile('attachments')) {
            // store the attachments and attach them to the post
            $attachments = $request->file('attachments');
            foreach ($attachments as $attachment) {
                $path = $attachment->store('public/attachments');
                $post->attachments()->create(['path' => $path]);
            }
        }

        $attachments = $post->attachments;

        foreach($attachments as $attachment){
            $path = str_replace('public', 'storage', $attachment->path);
            $attachment->path = $path;
        }

        $post->attachments = $attachments;
        $post->user = Auth::user();
        $post->comments = [];
        $post->count_likes = $post->likes->count();

        return response()->json(['success' => true, 'post' => $post], 201);
    }

    public function getPosts()
    {
        $posts = Post::with(['user.followers', 'attachments', 'likes', 'comments' => function($query) {
            $query->latest()->with('user');
        }])->where(function($query) {
            $query->where('privacy', 'public')
                  ->orWhere(function($query) {
                      $query->where('privacy', 'friends')
                            ->whereIn('user_id', function($query) {
                                $query->select('friend_id')->from('friends')
                                ->where('user_id', auth()->id())
                                ->orWhere('friend_id', auth()->id());
                            });
                  });
        })->orderBy('created_at', 'desc')->paginate(10);


        foreach($posts as $post){
            if($post->attachments){
                foreach($post->attachments as $attachment){
                    $path = str_replace('public', 'storage', $attachment->path);
                    $attachment->path = $path;
                }
            }
            $post->count_likes = $post->likes->count();
            $post->count_comments = $post->comments->count();
            if($post->comments){
                foreach($post->comments as $comment){
                    $path = str_replace('public', 'storage', $comment->attachment);
                    $comment->attachment = $path;
                }
            }
        }

        return response()->json(['success' => true, 'posts' =>$posts], 201);
    }

    public function deletePost($id)
    {
        $post = Post::find($id);
        $post->delete();
        return response()->json(['success' => true, 'message' => 'post successfully deleted.'], 201);
    }

    public function likePost($id) {
        $post = Post::find($id);
        $like = $post->likes()->where('user_id', Auth::id())->first();
        if($like){
            $like->delete();
            return response()->json(['success' => true, 'message' => 'unliked'], 201);
        }else{
            $post->likes()->create(['user_id' => Auth::id()]);
            return response()->json(['success' => true, 'message' => 'liked'], 201);
        }
    }

}
