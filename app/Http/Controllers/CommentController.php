<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index() 
    {
        return response()->json(Comment::with(['book' => function($query) {
            return $query->select(['id', 'name']);
        }])->select('id', 'comment', 'book_id', 'public_ip', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get(), 200);
    }

    public function findOne($id)
    {
        $comment = Comment::findOrFail($id);

        return response()->json($comment, 200);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'comment' => 'required',
        ]);

        $data = $request->only('user_id', 'book_id', 'comment');

        $data['public_ip'] = $request->ip();

        Comment::create($data);

        return response()->json(['message' => 'Comment Saved Successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'comment' => 'required',
        ]);

        $data = $request->only('user_id', 'book_id', 'comment');
        $data['public_ip'] = $request->ip();

        $comment = Comment::findOrFail($id);

        $comment->update($data);

        return response()->json(['message' => 'Comment Updated Successfully'], 201);
    }
}
