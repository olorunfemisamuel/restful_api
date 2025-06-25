<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CauseContoller;
use App\Http\Controllers\ContributionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/cause', [CauseContoller::class, 'retreive']);


Route::post('/cause', [CauseContoller::class, 'create']);

Route::get('/cause/{id}', [CauseContoller::class, 'show']);

Route::put('/cause/{id}', [CauseContoller::class, 'update']);

Route::delete('/cause/{id}', [CauseContoller::class, 'delete']);

Route::post('/cause/{id}/contribute', [ContributionController::class, 'contribute']);

