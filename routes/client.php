<?php

use Illuminate\Support\Facades\Route;

Route::view('/', '/client.app');
Route::view('/catalog', '/client.app');
Route::view('/catalog/{category}', '/client.app');
Route::view('/products/{product}', '/client.app');

