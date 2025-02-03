<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EdiController;

Route::post('convert-edi-to-json', [EdiController::class, 'convertEDIToJsonV1']);
Route::post('convert-edi-to-json-v2', [EdiController::class, 'convertEDIToJsonV2']);
Route::post('convert-json-to-edi', [EdiController::class, 'convertJsonToEdiV1']);
Route::post('convert-json-to-edi-v2', [EdiController::class, 'convertJsonToEdiV2']);
