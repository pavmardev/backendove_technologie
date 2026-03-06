<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookRestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = [
            ['id' => 1, 'title' => 'Tri gaštanové kone', 'author' => 'Margita Figuli'],
            ['id' => 2, 'title' => 'Jozef Mak', 'author' => 'Jozef Cíger Hronský'],
            ['id' => 3, 'title' => 'Tisícročná včela', 'author' => 'Peter Jaroš'],
        ];

        return response(['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        echo "Zobrazujem formulár pre pridanie novej knihy.";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $title = $request->input('title');
        $author = $request->input('author');
        return response("Kniha s názvom '$title' bola vytvorená.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response("Zobrazuje sa kniha s ID = $id.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        echo "Zobrazujem formulár pre úpravu knihy s ID = $id.";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $newTitle = $request->input('title');
        $author = $request->input('author');
        return response("Kniha s ID = $id bol upravená na '$newTitle' - '$author'.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response("Kniha s ID = $id bola vymazaná.");
    }
}
