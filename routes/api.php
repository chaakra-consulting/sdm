<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BukukasProjectSyncController;

Route::post('/bukukas-sync/project', [BukukasProjectSyncController::class, 'store']);