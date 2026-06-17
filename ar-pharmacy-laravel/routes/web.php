<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('processes.index');
});

Route::get('/workflow', function () {
    return view('processes.workflow');
});