<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BukukasProjectSyncController;
use App\Http\Controllers\API\CRMAPIController;
use App\Http\Controllers\API\UserAPIController;
use App\Http\Controllers\ProjectController;

Route::post('/bukukas-sync/project', [BukukasProjectSyncController::class, 'store']);

Route::get('project/progress', [ProjectController::class, 'getProjectProgress']);

Route::get('/users/index', [CRMAPIController::class, 'indexUser']);
Route::post('/tasks/store', [CRMAPIController::class, 'storeTask']);
