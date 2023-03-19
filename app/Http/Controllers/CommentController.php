<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    public function makeComment(Request $request, $postId)
    {
        // validate the request data
        //dd($request->all());
        $request->validate([
            'body' => 'required',
            //'attachment' => 'nullable|file|max:1024',
        ]);

        // find the post
        $post = Post::findOrFail($postId);

        //get the authenticated user
        $user = auth()->user();

        // create a new comment
        $comment = new Comment;
        $comment->body = $request->body;
        $comment->user()->associate($user);
        $comment->post()->associate($post);
        
        if ($request->hasFile('attachment')) {
            $attachments = $request->file('attachment');
            foreach ($attachments as $attachment) {
                $path = $attachment->store('public/comments');
                $comment->attachment = $path;
            }
        }
        $comment->save();

        if($comment->attachment){
            $path = str_replace('public', 'storage', $comment->attachment);
            $comment->attachment = $path;
        }
        $comment->body = str_replace(["\r\n", "\n", "\r"], '<br>', $comment->body);
        return response()->json(['success' => true, 'message' => 'comment successfully saved.', 'comment' => $comment], 201);

    }

    public function deleteComment($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['success' => false, 'message' => 'Comment not found.'], 404);
        }
        $comment->delete();
        return response()->json(['success' => true,'message' => 'Comment deleted successfully.'], 200);
    }

    public function editComment(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found.'], 404);
        }

        $comment->body = $request->input('body');
        $comment->save();

        return response()->json(['success' => true, 'message' => 'Comment updated successfully.', 'comment' => $comment], 200);
    }

    public function getMoreComments($postId, $page)
    {
        $post = Post::findOrFail($postId);
        $comments = $post->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);

        foreach($comments as $comment){
            $path = str_replace('public', 'storage', $comment->attachment);
            $comment->attachment = $path;
            $comment->body = str_replace(["\r\n", "\n", "\r"], '<br>', $comment->body);
        }
        return response()->json(['success' => true, 'comments' =>$comments], 201);
    }

}
