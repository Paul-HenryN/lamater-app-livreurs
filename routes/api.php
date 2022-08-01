<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StepController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Reports
Route::get('/reports', [ReportController::class, 'index']);
Route::get('/report/{id}', [ReportController::class, 'show']);
Route::post('/report/create', [ReportController::class, 'store']);

// Steps
Route::get('/step/{id}', [StepController::class, 'show']);
Route::post('/step/create', [StepController::class, 'store']);
