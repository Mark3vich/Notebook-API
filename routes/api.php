<?php

use App\Http\Controllers\NotebookController;

Route::prefix('v1/notebook')->group(function () {
    Route::get('/', [NotebookController::class, 'index']);
    Route::post('/', [NotebookController::class, 'store']);
    Route::get('/{id}', [NotebookController::class, 'show']);
    Route::post('/{id}', [NotebookController::class, 'update']);
    Route::delete('/{id}', [NotebookController::class, 'destroy']);
});

