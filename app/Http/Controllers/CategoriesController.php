<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = DB::table('categories')
            ->get();

        return response()->json(['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::table('categories')
            ->insert([
                'name' => $request->name,
                'created_at' => now(),
                'updated_at' => now()
            ]);

        return response('Kategoria bola uspešne pridana', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categories = DB::table('categories')
            ->where('id', $id)
            ->get();

        return response()->json(['category' => $categories], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::table('categories')
            ->where('id', $id)
            ->update(['name' => $request->name]);

        return response('Kategoria bola aktualizovana', Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('categories')
            ->where('id', $id)
            ->delete();

        return response('Kategoria bola odstranena', Response::HTTP_OK);
    }
}
