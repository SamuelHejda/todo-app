<?php

use App\Models\Task;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $tasks = Task::with('tags')->latest()->get();

    return Inertia::render('Tasks/Index', [
        'tasks' => $tasks,
    ]);
})->name('web.tasks.index');

Route::post('/tasks', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    Task::create($request->only('name', 'description'));

    return redirect()->route('web.tasks.index');
});

Route::patch('/tasks/{task}', function (Request $request, Task $task) {
    $task->update($request->only('name', 'description', 'completed'));

    return redirect()->route('web.tasks.index');
});

Route::delete('/tasks/{task}', function (Task $task) {
    $task->delete();

    return redirect()->route('web.tasks.index');
});

Route::post('/tasks/{task}/tags', function (Request $request, Task $task) {
    $request->validate(['tag' => 'required|string|max:255']);

    $tag = Tag::firstOrCreate(['name' => $request->input('tag')]);
    $task->tags()->syncWithoutDetaching($tag->id);

    return redirect()->route('web.tasks.index');
});

Route::delete('/tasks/{task}/tags', function (Request $request, Task $task) {
    $request->validate(['tag' => 'required|string|max:255']);

    $tag = Tag::where('name', $request->input('tag'))->first();

    if ($tag) {
        $task->tags()->detach($tag->id);
    }

    return redirect()->route('web.tasks.index');
});