<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/projects/status-overview',   [DashboardController::class, 'getProjectStatusOverview']);
Route::get('/projects/by-area',           [DashboardController::class, 'getProjectsByArea']);
Route::get('/projects/budget-summary',    [DashboardController::class, 'getBudgetSummary']);
Route::get('/projects/size-summary',      [DashboardController::class, 'getProjectSizeSummary']);
Route::get('/projects/priority-projects', [DashboardController::class, 'getPriorityProjects']);
