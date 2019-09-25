<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

use App\Comment;

class CommentController extends Controller
{
    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    public function index(Request $request)
    {
        return Comment::where('article_id', $request->article_id)->orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request) // create new article
    {
        $comment = new Comment();
        $comment->fill($request->all());
        $comment->save();
        return [
            'success' => true
        ];
    }
}
