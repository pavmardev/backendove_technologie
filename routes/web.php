<?php

use App\Http\Controllers\ExampleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/example/create', [ExampleController::class, 'create']);
//Route::post('/example/result', [ExampleController::class, 'create']);

Route::get('/example/create', function () {
    return view('form.input');
});
Route::post('/example/result',function (Request $request) {
    try {
        $request->validate([
            'n' => 'required|integer'
        ]);
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
});
