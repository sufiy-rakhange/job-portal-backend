<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/jobs', [JobController::class, 'store']);
    Route::put('/jobs/{id}', [JobController::class, 'update']);
    Route::delete('/jobs/{id}', [JobController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->get('/my-jobs', function (Request $request) {
    return $request->user()->myJobs()->paginate(5);
});

Route::get('/jobs', [JobController::class, 'index']);

Route::middleware('auth:sanctum')->get('/test-user', function (Request $request) {
    return response()->json($request->user());
});