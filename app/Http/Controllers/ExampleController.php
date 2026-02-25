<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function create(Request $request) {
        try {
            $value = (int) $request->input('n');
            $list = array();
            for ($i = 1; $i <= 10; $i++) {
                array_push($list, $i*$value);
            }

            return view('form.input', ['list' => $list]);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            echo $errorMessage;
        }
    }
}
