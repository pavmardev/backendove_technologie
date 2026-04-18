<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Note $note)
    {
        // kto môže vidieť note, môže vidieť aj jej tasky
        $this->authorize('view', [Task::class, $note]);

        $tasks = $note->tasks()
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'tasks' => $tasks,
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Note::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'body'  => ['nullable', 'string'],
            'is_done' => ['required']
        ]);

        $task = Task::create([
            'title'     => $validated['title'],
            'body'      => $validated['body'] ?? null,
            'is_done'    => $validated['is_done'] ?? 'draft',
        ]);

        return response()->json(['task' => $task]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['Nenaslo sa'], Response::HTTP_NOT_FOUND);
        }
        $this->authorize('view', $task);
        return response()->json(['task' => $task], Response::HTTP_OK);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note, Task $task)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'is_done' => ['sometimes', 'boolean'],
            'due_at' => ['nullable', 'date'],
        ]);

        $task->update($validated);

        return response()->json([
            'message' => 'Úloha bola úspešne aktualizovaná.',
            'task' => $task,
        ], Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                'message' => 'Task nenájdený.'
            ], Response::HTTP_NOT_FOUND);
        }
        $this->authorize('delete', $task);
        $task->delete();
        return response()->json(['message' => 'Task deleted'], Response::HTTP_OK);
    }
}
