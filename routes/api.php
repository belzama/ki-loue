<?php

use Illuminate\Support\Facades\Route;
use App\Models;

Route::apiResource('publications', PublicationController::class);
Route::apiResource('dispositifs', DispositifController::class);
Route::apiResource('categories', CategorieController::class);
Route::apiResource('pays', PaysController::class);
Route::apiResource('villes', VilleController::class);
