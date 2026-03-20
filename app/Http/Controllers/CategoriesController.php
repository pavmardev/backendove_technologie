<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query();

        return response()->json(['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*DB::table('categories')
            ->insert([
                'name' => $request->name,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        */
        Category::create([
            'name' => $request->name
        ]);

        return response('Kategoria bola uspešne pridana', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        /*$categories = DB::table('categories')
            ->where('id', $id)
            ->get();
        */
        $categories = Category::query()->find($id);

        if (!$categories) {
            return response('Kategoria nenajdena', Response::HTTP_NOT_FOUND);
        }

        return response()->json(['category' => $categories], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        /*DB::table('categories')
            ->where('id', $id)
            ->update(['name' => $request->name]);
        */
        $category = Category::query()->find($id);

        if (!$category) {
            return response('Kategoria nebola najdena', Response::HTTP_NOT_FOUND);
        }

        $category->update([
            'name' => $request->name
        ]);

        return response('Kategoria bola aktualizovana', Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        /*DB::table('categories')
            ->where('id', $id)
            ->delete();

        */
        $category = Category::query()->find($id);

        if (!$category) {
            return response('Kategoria nebola najdena', Response::HTTP_NOT_FOUND);
        }

        $category->delete();

        return response('Kategoria bola odstranena', Response::HTTP_OK);
    }
}
