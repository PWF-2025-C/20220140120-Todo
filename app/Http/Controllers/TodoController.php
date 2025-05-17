<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;

class TodoController extends Controller
{
    public function index()
    {
        // $todos = Todo::with('category', Auth::id())->orderBy('is_done', 'desc')->get();
        $todos = Todo::with('category')
            ->where('user_id', Auth::id())
            ->orderBy('is_done', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $todosCompleted = Todo::where('user_id', Auth::id())
            ->where('is_done', true)
            ->count();

        return view('todo.index', compact('todos', 'todosCompleted'));
    }

    public function create()
    {
        $categories = Category::all(); 
        return view('todo.create', compact('categories'));
    }

    //Edit Data – Todo
    public function edit($id)
    {
        $todo = Todo::findOrFail($id);
        $categories = Category::all();
        
        return view('todo.edit', compact('todo', 'categories'));
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        // Practical
        // $todo->title = $request->title;
        // $todo->save();

        // Eloquent Way - Readable
        $todo->update([
            'title' => ucfirst($request->title),
            'category_id' => $request->category_id,
        ]);        

        return redirect()->route('todo.index')->with('success', 'Todo updated successfully!');
    }
    
    //delete data - todo
    public function destroy(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->delete();
            return redirect()->route('todo.index')->with('success', 'Todo deleted successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to delete this todo!');
        }
    }

    public function destroyCompleted()
    {
        // get all todos for current user where is_completed is true
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
            ->where('is_done', true)
            ->get();

        foreach ($todosCompleted as $todo) {
            $todo->delete();
        }

        // dd($todosCompleted);
        return redirect()->route('todo.index')->with('success', 'All completed todos deleted successfully!');
    }

    public function complete(Todo $todo)
    {
        if ($todo->user_id != Auth::id()) {
            abort(403);
        }

        $todo->is_done = true;
        $todo->save();

        return redirect()->route('todo.index')->with('success', 'Todo marked as complete.');
    }

    public function uncomplete(Todo $todo)
    {

        if ($todo->user_id != Auth::id()) {
            abort(403);
        }

        $todo->is_done = false;
        $todo->save();

        return redirect()->route('todo.index')->with('success', 'Todo marked as uncomplete.');
    }

    public function store(Request $request)
    {
    $request->validate([
        'title' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id'
    ]);
    
    $todo = new Todo();
    $todo->title = ucfirst($request->title);
    $todo->is_done = false;
    $todo->user_id = Auth::user()->id;
    $todo->category_id = $request->category_id;
    $todo->save();

    return redirect()->route('todo.index')->with('success', 'Todo Created Successfully');
}

    public function show(string $id)
    {
        $todo = Todo::with('category')->findOrFail($id);
        return view('todo.show', compact('todo'));
    }    
}
