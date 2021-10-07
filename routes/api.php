<?php

use App\Http\Controllers\API\DataDoctorController;
use App\Http\Controllers\API\DoctorCategoryController;
use App\Http\Controllers\API\DoctorController;
use App\Http\Controllers\API\HospitalController;
use App\Http\Controllers\API\HospitalDataController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
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


Route::post('register', [UserController::class, 'register']);
Route::post('registeran', [RegisterController::class, 'register']);

Route::post('login', [UserController::class, 'login']);
Route::post('loginan', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){

    Route::middleware(['admin'])->group(function () {
        Route::get('/admin_check', function () {
            return response()->json([
                'status' => 200
            ], 200);
        });

        Route::post('/add-hospital-data', [HospitalDataController::class, 'create']);
        Route::post('/add-doctor-category', [DoctorCategoryController::class, 'create']);
        Route::post('/add-doctor', [DataDoctorController::class, 'create']);

        Route::post('/shop/delete/{id}', [ShopController::class, 'delete']);
        Route::post('/shop/add', [ShopController::class, 'store']);

        Route::post('/article/create', [HealthInformationController::class, 'post']);
        Route::post('/article/draft', [HealthInformationController::class, 'draft']);
        Route::post('/article/delete', [HealthInformationController::class, 'delete']);
        Route::get('/article/label', [HealthInformationController::class, 'showLabel']);
        Route::get('/article/show', [HealthInformationController::class, 'showAllArticle']);

        Route::get('/admin/profile', [AdminController::class, 'getProfile']);
    });

    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'updateProfile']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('transaction', [TransactionController::class, 'process']);

    Route::get('doctors',[DoctorController::class, 'all']);
    Route::get('categories',[DoctorCategoryController::class, 'all']);
    Route::get('hospitals',[HospitalController::class, 'all']);
});





