<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function index()
    {
        $todos = auth()->user()->todos;
        return response()->json(['success' => true, 'todos' =>$todos], 201);
    }

    public function createTodo(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $todo = new Todo;
        $todo->title = $request->title;
        $todo->user()->associate(auth()->user());
        $todo->save();

        return response()->json(['success' => true, 'todo' => $todo], 201);
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'completed' => 'required|boolean',
        ]);

        $todo->completed = $request->completed;
        $todo->save();

        return response()->json(['success' => true, 'todo' => $todo], 201);
    }

    public function completeTodo($id)
    {
        $todo = Todo::find($id);
        $todo->completed = true;
        $todo->save();
        return response()->json(['success' => true, 'todo' => $todo], 201);
    }

    public function destroy($id)
    {
        $todo = Todo::find($id);
        $todo->delete();
        return response()->json(['success' => true, 'message' => 'Todo successfully deleted.'], 201);
    }

}
