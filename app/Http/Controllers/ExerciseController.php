<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function pozicanieKnihy(Request $request)
    {
        $data = $request->input('kniha');

        return response("Pozicali ste si knihu: {$data}");
    }

    public function vratenieKnihy(Request $request, int $year) {
        $data = $request->input('kniha');

        return response("Vratili ste si knihu: {$data} s rokom vydania {$year}");
    }
}
