<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookRpcController extends Controller
{
    public function borrowBook(Request $request, int $id)
    {
        $userId = $request->input('user_id');

        return response(
            "Používateľ s ID {$userId} si požičal knihu s ID {$id}."
        );
    }

    public function returnBook(Request $request, int $id)
    {
        $condition = $request->input('condition');

        return response(
            "Kniha s ID {$id} bola vrátená v stave: {$condition}."
        );
    }
}
