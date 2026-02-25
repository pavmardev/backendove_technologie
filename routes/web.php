<?php

use App\Http\Controllers\ExampleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/example/create', [ExampleController::class, 'create']);
Route::post('/example/result', [ExampleController::class, 'create']);
