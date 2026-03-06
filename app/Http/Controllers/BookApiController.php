<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookApiController extends Controller
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

        return response()->json(['data' => $books], Response::HTTP_OK);
    }

//    public function index(BookService $bookService)
//    {
//        $books = $bookService->getAllBooks();
//
//        return response()->json(['data' => $books], Response::HTTP_OK);
//    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $book = [
            'title'  => $request->input('title'),
            'author' => $request->input('author'),
        ];

        return response()->json([
            'message' => 'Kniha bola vytvorená',
            'data'    => $book,
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = [
            'id'     => $id,
            'title'  => 'Tri gaštanové kone',
            'author' => 'Margita Figuli',
        ];

        return response()->json([
            'data' => $book],
            Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = [
            'id'     => $id,
            'title'  => $request->input('title'),
            'author' => $request->input('author'),
        ];

        return response()->json([
            'message' => 'Kniha bola upravená',
            'data'    => $book,
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json([
            'message' => "Kniha s ID {$id} bola vymazaná"
        ], Response::HTTP_OK);
    }
}
