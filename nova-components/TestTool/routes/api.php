<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Zuse\TestTool\Http\Controllers\TestToolController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

// Get all users
Route::get('/users', [TestToolController::class, 'index']);

// Store form data to database
Route::post('/submit', [TestToolController::class, 'store']);
