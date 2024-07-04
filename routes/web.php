<?php

use App\Http\Controllers\ProductIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductIndex::class, 'get']);
Route::post('/', [ProductIndex::class, 'post']);
