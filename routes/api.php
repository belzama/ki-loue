<?php

// routes/api.php
use App\Http\Controllers\Api\ApiController;

Route::prefix('v1')->group(function () {
    Route::get('/publications', [ApiController::class, 'publications']);
    Route::get('/categories', [ApiController::class, 'categories']);
    Route::post('/publications/{publication}/reservations', [ApiController::class, 'storeReservation']);
});
