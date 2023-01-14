<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::check()){
            $validator = Validator::make($request->all(), [
                'comment' => 'required|string',
            ]);

            if($validator->fails()){
                return redirect()->back()->with('status', 'Comment field is Mandatory');
            }
            $post = Post::where('slug', $request->post_slug)->first();
            if($post){
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => Auth::user()->id,
                    'comment' => $request->comment,
                ]);
                return redirect()->back()->with('status', 'Comment Send Successfully');
            }else {

            }
        }else {
            return redirect('/login')->with('status', 'Login Fist to Comment');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(Auth::check()){
            $comment = Comment::where('id',$request->comment_id)->where('user_id', Auth::user()->id )->first();
            $comment->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Comment Delted successfully',
            ]);

        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to delete this Comment',
            ]);
        }

    }
}
