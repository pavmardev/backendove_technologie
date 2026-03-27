<?php

namespace App\Http\Controllers;
use App\Models\Note;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*$notes = DB::table('notes')
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->get();
        */

        /*
        $notes = Note::query()
            ->orderByDesc('notes.updated_at')
            ->get();

        return response()->json(['notes' => $notes], Response::HTTP_OK);
    }*/
        $notes = Note::query()
            ->select(['id', 'user_id', 'title', 'body', 'status', 'is_pinned', 'created_at'])
            ->with([
                'user:id,first_name,last_name',
                'categories:id,name,color',
            ])
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'notes' => $notes,
        ], Response::HTTP_OK);


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*DB::table('notes')->insert([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'body' => $request->body,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        */

        /*$note = Note::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'body' => $request->body,
        ]);


        return response()->json(['message' => 'Note created', 'note' => $note], Response::HTTP_CREATED);
        */

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],

            'title' => ['required', 'string', 'min:3', 'max:255'],
            'body'  => ['nullable', 'string'],
            'status' => ['sometimes', 'required', 'string', Rule::in(['draft', 'published', 'archived'])],
            'is_pinned' => ['sometimes', 'boolean'],

            'categories' => ['sometimes', 'array', 'max:3'],
            'categories.*' => ['integer', 'distinct', 'exists:categories,id'],
        ]);

        $note = Note::create([
            'user_id'   => $validated['user_id'],
            'title'     => $validated['title'],
            'body'      => $validated['body'] ?? null,
            'status'    => $validated['status'] ?? 'draft',
            'is_pinned' => $validated['is_pinned'] ?? false,
        ]);

        if (!empty($validated['categories'])) {
            $note->categories()->sync($validated['categories']);
        }

        return response()->json([
            'message' => 'Poznámka bola úspešne vytvorená.',
            'note' => $note->load([
                'user:id,first_name,last_name',
                'categories:id,name,color',
            ]),
        ], Response::HTTP_CREATED);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        /*$note = DB::table('notes')
            ->whereNull('deleted_at')
            ->where('id', $id)
            ->first();

        if (!$note) {
            return response()->json(['message' => 'Note not found'],
                Response::HTTP_NOT_FOUND);
        }
        */

        $note = Note::with(['user:id,first_name,last_name', 'categories:id,name,color',
                            'comments:id,user_id,body',
                            'tasks.comments:id,user_id,body',])
            ->find($id);
            if (!$note) {
                return response()->json(['message' => 'Note not found'], Response::HTTP_NOT_FOUND);
            }

        return response()->json(['note' => $note], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        /*$note = Note::find($id);

        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }

        /*DB::table('notes')->where('id', $id)->update([
            'title' => $request->title,
            'body' => $request->body,
            'updated_at' => now(),
        ]);
        */
        /*$note->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return response()->json(['message' => 'Note updated'], Response::HTTP_OK);
        */
        $note = Note::find($id);

        if (!$note) {
            return response()->json(
                ['message' => 'Poznámka nenájdená.'],
                Response::HTTP_NOT_FOUND
            );
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['nullable', 'string'],
            'status' => ['sometimes', 'required', 'string', Rule::in(['draft', 'published', 'archived'])],
            'is_pinned' => ['sometimes', 'boolean'],
            'categories' => ['sometimes', 'array'],
            'categories.*' => ['integer', 'distinct', 'exists:categories,id'],
        ]);

        // aktualizujeme iba to, čo prešlo validáciou
        $note->update($validated);

        // spoj. tabulku synchronizujeme iba ak boli poslané idčka
        if (array_key_exists('categories', $validated)) {
            $note->categories()->sync($validated['categories']);
        }

        return response()->json([
            'message' => 'Poznámka bola aktualizovaná.',
            'note' => $note->load([
                'user:id,first_name,last_name',
                'categories:id,name,color',
            ]),
        ], Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }

        /*DB::table('notes')->where('id', $id)->update([
            'deleted_at' => now(),
            'updated_at' => now(),
        ]);
        */
        $note->delete();

        return response()->json(['message' => 'Note deleted'], Response::HTTP_OK);
    }

    public function statsByStatus()
    {
        /*$stats = DB::table('notes')

            ->select('status', DB::raw('count(*) as total'))

            ->groupBy('status')

            ->get();
        */
        $stats = Note::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        return response()->json(['stats' => $stats], Response::HTTP_OK);


    }
    public function archiveOldDrafts()
    {
        /*$affected = DB::table('notes')
            ->where('status', 'draft')
            ->where('updated_at', '<', now()->subDays(30))
            ->update(['status' => 'archived',
                'updated_at' => now()]);
        */

        $affected = Note::query()
            ->where('status', '=', 'draft')
            ->where('updated_at', '<', now()->subDays(30))
            ->update(['status' => 'archived']);

        return response()->json(['message' => 'Notes archived', 'affected_rows' => $affected], Response::HTTP_OK);

    }

    public function userNotesWithCategories(string $userId)
    {
        /*$notes = DB::table('notes')
            ->join('note_category', 'notes.id', '=', 'note_category.note_id')
            ->join('categories', 'note_category.category_id', '=', 'categories.id')
            ->where('notes.user_id', $userId)
            ->orderBy('notes.updated_at', 'desc')
            ->select('notes.id', 'notes.title', 'categories.name as category')
            ->get();
        */
        $notes = Note::with(['categories:id,name'])
            ->where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->get(['id', 'title']);

        return response()->json([
            'notes' => $notes
        ]);

    }

    public function search(Request $request)
    {
        /*$q = trim((string) $request->query('q', ''));

        $notes = DB::table('notes')
            ->whereNull('deleted_at')
            ->where('status', 'published')
            ->where(function ($x) use ($q) {
                $x->where('title', 'like', "%{$q}%")
                    ->orWhere('body', 'like', "%{$q}%");
            })
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'query' => $q,
            'notes' => $notes,
        ], Response::HTTP_OK);
        */
        // ORM
        $q = trim((string) $request->query('q', ''));

        $notes = Note::searchPublished($q);

        return response()->json(['query' => $q, 'notes' => $notes], Response::HTTP_OK);

    }

    public function notesByUser(int $user_id) {
        /*$notes = DB::table('notes')
            ->select('notes.title')
            ->where('user_id', $user_id)
            ->get();
        */
        $notes = Note::query()
            ->select('notes.title')
            ->where('user_id', '=', $user_id)
            ->get();
        return response()->json(['notes' => $notes], Response::HTTP_OK);

    }

    public function publish(string $id) {
        $note = Note::find($id);

        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }
        $note = Note::publishNote($id);

        return response()->json(['note' => $note], Response::HTTP_OK);
    }

    public function archive(string $id) {
        $note = Note::find($id);

        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }
        $note = Note::archiveNote($id);

        return response()->json(['note' => $note], Response::HTTP_OK);
    }

    public function pin(string $id) {
        $note = Note::find($id);

        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }
        $note = Note::pinNote($id);

        return response()->json(['note' => $note], Response::HTTP_OK);
    }

    public function unpin(string $id) {
        $note = Note::find($id);

        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }
        $note = Note::unpinNote($id);

        return response()->json(['note' => $note], Response::HTTP_OK);
    }
}
