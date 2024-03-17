<?php


use App\Http\Controllers\RolesController;


Route::prefix('owner/')->group(function () {
    Route::put('/roles/{role}', [RolesController::class, 'update']);
});
