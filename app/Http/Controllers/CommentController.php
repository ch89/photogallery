<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Photo;

class CommentController extends Controller {
    public function __construct() {
        $this->middleware("auth");
    }

    public function store(Photo $photo) {
        $comment = new Comment(request()->validate([
            "content" => "required"
        ]));

        $comment->user_id = auth()->id();

        return $photo->comments()->save($comment);
    }

    public function destroy(Comment $comment) {
        $comment->delete();
    }
}