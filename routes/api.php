<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', fn() => response()->json(['status' => 'ok']));
