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

// Store new report
Route::post('/reports/create', [ReportController::class, 'store']);
// Show report details
Route::get('/reports/{reportId}', [ReportController::class, 'show']);
// Delete report
Route::delete('/reports/{reportId}', [ReportController::class, 'destroy']);
// Update report
Route::put('/reports/{reportId}', [ReportController::class, 'update']);

// Create step in specified report
Route::post('/reports/{reportId}/steps/create', [StepController::class, 'store']);
Route::get('/step/{id}', [StepController::class, 'show']);
