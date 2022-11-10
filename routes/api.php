<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MiscMstController;
use App\Http\Controllers\PlanController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
    Route::group(['middleware' => ['auth:api']], function(){
        Route::get('get_my_profile',[UserController::class,'getMyProfile']);    
        Route::get('/user', function (Request $request){
            return $request->user();
        });
    Route::post('update_user_profile',[UserController::class,'updateUserProfile']);
    Route::post('user_image_Delete',[UserController::class,'userImageDelete']);
    Route::post('like_user',[UserController::class,'likeUser']);
    Route::get('get_match_profile',[UserController::class,'getMatchProfile']);
    Route::get('get_all_interested_user',[UserController::class,'getAllInterestedUser']);
    Route::get('get_user_profile/{id}',[UserController::class,'getUserProfile']);
});
Route::get('get_gender',[MiscMstController::class,'getGender']);
Route::get('intrested_in',[MiscMstController::class,'intrestedIn']);
Route::get('sexual_orientation',[MiscMstController::class,'sexualOrientation']);
Route::get('get_plan',[PlanController::class,'getPlan']);
Route::get('login', [AuthController::class, 'unauthorizedUser'])->name('login');
Route::post('register_with_mobile',[AuthController::class,'registerWithMobile']);
Route::post('register_with_social_media',[AuthController::class,'registerWithSocialMedia']);
Route::post('login_with_mobile',[AuthController::class,'loginWithMobile']);
Route::post('login_with_social_media',[AuthController::class,'loginWithSocialMedia']);
Route::post('otp_verify',[AuthController::class,'otpVerify']);