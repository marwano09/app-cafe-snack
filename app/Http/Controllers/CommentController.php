<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'desc'); // default latest first
        $comments = Comment::with('user')
            ->orderBy('created_at', $sort)
            ->paginate(10);

        return view('comments.index', compact('comments','sort'));
    }

    public function create()
    {
        return view('comments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        Comment::create([
            'body' => $data['body'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('comments.index')->with('ok','تمت إضافة التعليق ✅');
    }

    public function edit(Comment $comment)
    {
        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update',$comment); // optional
        $data = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment->update($data);

        return redirect()->route('comments.index')->with('ok','تم تحديث التعليق ✅');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete',$comment); // optional
        $comment->delete();
        return back()->with('ok','تم حذف التعليق 🗑️');
    }
}
