<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'commentStory' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back();
        } else {
            Comment::create([
                'userID' => Auth::id(),
                'userName' => Auth::user()->name,
                'commentStory' => request()->commentStory,
            ]);
            return redirect()->back();
        }
    }

    public function index()
    {
        $comments = $this->comment->latest()->paginate(10);
        return view('welcome', compact('comments'));
    }
}
