<?php

use App\Http\Controllers\BookApiController;
use App\Http\Controllers\BookRestController;
use App\Http\Controllers\BookRpcController;
use App\Http\Controllers\BookSacController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestTimeController;
use App\Http\Controllers\RpcTimeController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\InvokableExerciseController;
use \App\Http\Controllers\ErestApiController;
use App\Http\Controllers\ErpcController;

Route::post('/rpc/books/{id}/borrow', [BookRpcController::class, 'borrowBook']);
Route::post('/rpc/books/{id}/return', [BookRpcController::class, 'returnBook']);
Route::post('/example', [ExerciseController::class, 'pozicanieKnihy']);
Route::post('example/{year}', [ExerciseController::class, 'vratenieKnihy']);


Route::get('invokable/{id}', InvokableExerciseController::class);
Route::get('/sac/books/{id}', BookSacController::class);

Route::prefix('rest')->group(function () {
    Route::resource('books', BookRestController::class);
});
Route::get('/books', [BookRestController::class, 'index']);
Route::get('/books/create', [BookRestController::class, 'create']);
Route::post('/books', [BookRestController::class, 'store']);
Route::get('/books/{id}', [BookRestController::class, 'show']);
Route::get('/books/{id}/edit}', [BookRestController::class, 'edit']);
Route::put('/books/{id}', [BookRestController::class, 'update']);
Route::delete('/books/{id}', [BookRestController::class, 'destroy']);

Route::prefix('restapi')->group(function () {
    Route::apiresource('books', BookApiController::class);
});

Route::get('/currtime', [ErestApiController::class, 'getTime']);
Route::get('/currtime/rpc', [ErpcController::class, 'getTime']);

Route::get('/time', [RestTimeController::class, 'getTime']);
Route::get('/rpc/time', [RpcTimeController::class, 'getTime']);

