<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\BackgroundController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'guest'], function () {
    Route::post('user/register', [RegisterController::class, 'registerByEmail']);
    Route::post('email/verify', [RegisterController::class, 'emailVerify']);
    Route::post('email/verify/resend', [RegisterController::class, 'resendVerifyEmail']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user', [UserController::class, 'getUser']);

    Route::post('user/change/password', [UserController::class, 'changePassword']);
    Route::post('user/update', [UserController::class, 'updateUserData']);
    Route::post('user/change/email', [UserController::class, 'changeEmail']);

    Route::post('reset/password/email/request', [ResetPasswordController::class, 'resetByEmailRequest']);

    Route::group(['middleware' => 'verifiedEmail'], function () {
        Route::post('track/add/favorites', [TrackController::class, 'addTrackInFavorite']);
        Route::post('track/delete/favorites', [TrackController::class, 'deleteTrackInFavorite']);
        Route::get('tracks/favorites', [TrackController::class, 'getTracksFavoritesUser']);
        Route::get('tracks/favorites/id', [TrackController::class, 'getIdTracksFavoritesUser']);

        Route::get('tracks/downloads', [TrackController::class, 'getDownloadsTracks']);
        Route::get('tracks/downloads/id', [TrackController::class, 'getIdDownloadsTracks']);

        Route::get('download/license/{oTrack}', [TrackController::class, 'downloadLicenseForTrack']);
    });
});

Route::get('user/login', [AuthController::class, 'loginByEmail']);

Route::get('user/social/{sProvider}', [UserController::class, 'redirecToProvider']);
Route::post('user/{sProvider}/callback', [UserController::class, 'handleProviderCallback']);

Route::get('background', [BackgroundController::class, 'getBackground']);

Route::get('track/genres', [TrackController::class, 'getGenres']);
Route::get('track/moods', [TrackController::class, 'getMoods']);
Route::get('track/usage_types', [TrackController::class, 'getUsageTypes']);
Route::get('track/tags', [TrackController::class, 'getTags']);

Route::get('track/{oTrack}', [TrackController::class, 'getDataTrack']);
Route::get('tracks', [TrackController::class, 'getDataTracks']);

Route::get('download/track/{oTrack}', [TrackController::class, 'downloadTrack']);
Route::get('download/track/from/user/{oTrack}', [TrackController::class, 'downloadTrackFromUser']);

Route::get('faq', [FaqController::class, 'getFaq']);
Route::get('privacy_policy', [FaqController::class, 'getPrivacyPolicy']);
Route::get('user_terms', [FaqController::class, 'getUseTerm']);

Route::get('tracks/filter', [TrackController::class, 'filterTracks']);

Route::get('search/track', [TrackController::class, 'searchTrack']);

Route::get('tracks/top', [TrackController::class, 'getTrackTop']);
