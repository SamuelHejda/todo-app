<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\Tag;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Task::with('tags');

    if ($request->has('tag')) {
        $query->whereHas('tags', function ($q) use ($request) {
            $q->where('name', $request->input('tag'));
        });
    }

    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    if ($request->has('completed')) {
        $query->where('completed', $request->boolean('completed'));
    }

    return $query->paginate(10);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());

        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return $task->load('tags');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return $task;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(null, 204);
    }

    /**
     * Attach a tag to the task.
     */
    public function addTag(Request $request, Task $task)
    {
        $request->validate(['tag' => 'required|string|max:255']);

        $tag = Tag::firstOrCreate(['name' => $request->input('tag')]);
        $task->tags()->syncWithoutDetaching($tag->id);

        return $task->load('tags');
    }

    /**
     * Remove a tag from the task.
     */
    public function removeTag(Request $request, Task $task)
    {
        $request->validate(['tag' => 'required|string|max:255']);

        $tag = Tag::where('name', $request->input('tag'))->first();

        if ($tag) {
            $task->tags()->detach($tag->id);
        }

        return $task->load('tags');
    }
}