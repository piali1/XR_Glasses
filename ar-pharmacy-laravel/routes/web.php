<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProcessApiController;

Route::get('/', function () {
    return view('processes.index');
});

Route::get('/workflow', function () {
    return view('processes.workflow');
});


Route::post('/api/batches', [ProcessApiController::class, 'createBatch']);
Route::post('/api/materials/validate', [ProcessApiController::class, 'validateMaterial']);
Route::post('/api/process-logs', [ProcessApiController::class, 'storeProcessLog']);
Route::post('/api/issues', [ProcessApiController::class, 'storeIssue']);
Route::post('/api/batches/{batch}/complete', [ProcessApiController::class, 'completeBatch']);
Route::get('/api/history', [ProcessApiController::class, 'history']);

