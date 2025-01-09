<?php

use App\Http\Controllers\TemporaryLinkController;
use App\Http\Controllers\UserPageAController;
use App\Http\Middleware\CheckUserId;
use Illuminate\Support\Facades\Route;

Route::get('/', [TemporaryLinkController::class, 'index'])->name('mainPage');
Route::post('/register', [TemporaryLinkController::class, 'register']);

Route::get('/pageA', [UserPageAController::class, 'index'])->name('pageA');

Route::group(['middleware' => [CheckUserId::class]], function () {
    Route::get('/regenerateLink/{user}', [TemporaryLinkController::class, 'regenerateLink'])
        ->name('regenerateLink');
    Route::delete('/deactivateTmpLink/{user}/{userTmpLink}', [TemporaryLinkController::class, 'deactivateTmpLink'])
        ->name('deactivateTmpLink');

    Route::get('/runRoll/{user}', [UserPageAController::class, 'runRoll'])->name('runRoll');
    Route::get('/rollHistory/{user}', [UserPageAController::class, 'userHistory'])->name('userHistory');
});
