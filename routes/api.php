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

// Get all reports
Route::get('/reports', [ReportController::class, 'index']);
// Store new report
Route::post('/reports/create', [ReportController::class, 'store']);
// Show specified report details
Route::get('/reports/{reportId}', [ReportController::class, 'show']);
// Update specified report
Route::put('/reports/{reportId}', [ReportController::class, 'update']);
// Delete report
Route::delete('/reports/{reportId}', [ReportController::class, 'destroy']);

// Create step in specified report
Route::post('/reports/{reportId}/steps/create', [StepController::class, 'store']);
// Show specified step details in specified report
Route::get('/reports/{reportId}/steps/{stepId}', [StepController::class, 'show']);
// Update specified step in specified report
Route::put('/reports/{reportId}/steps/{stepId}', [StepController::class, 'update']);
// Delete specified step in specified report
Route::delete('/reports/{reportId}/steps/{stepId}', [StepController::class, 'destroy']);
// Delete specified file of specified step in specified report
Route::delete('/reports/{reportId}/steps/{stepId}/files/{fileId}', [StepController::class, 'destroyFile']);
