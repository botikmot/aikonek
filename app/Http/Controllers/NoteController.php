<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    public function index()
    {
        $notes = auth()->user()->notes;
        return response()->json(['success' => true, 'notes' =>$notes], 201);
    }

    public function store(Request $request)
    {
        $note = new Note;
        $note->title = $request->input('title');
        $note->content = $request->input('content');
        $note->user()->associate(auth()->user());
        $note->save();
        return response()->json(['success' => true, 'note' => $note], 201);
    }

    public function updateNote(Request $request, $id)
    {
        $note = Note::find($id);
        $note->title = $request->input('title');
        $note->content = $request->input('content');
        $note->save();
        return response()->json(['success' => true, 'note' => $note], 201);
    }
}
