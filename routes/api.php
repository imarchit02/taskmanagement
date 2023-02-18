<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\apiController as api;

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

//Proteced API with token
Route::middleware('auth:sanctum')->group(function() {
    
    //Board CRUD Api
    Route::get('getBoardData/{id}',[api::class,'fetchBoardData']);
    Route::post('putBoardData',[api::class,'InsertBoardData']);
    Route::put('updateBoardData/{id}',[api::class,'UpdateBoardData']);
    Route::delete('deleteBoardData/{id}',[api::class,'DeleteBoardData']);

   //Task CRUD Api
    Route::get('getTaskData/{id}',[api::class,'fetchTaskData']);
    Route::post('putTaskData',[api::class,'InsertTaskData']);
    Route::put('updateTaskData/{id}',[api::class,'updateTaskData']);
    Route::delete('deleteTaskData/{id}',[api::class,'deleteTaskData']);

    //Logout Api
    Route::get('logoutUser',[api::class,'logout']);
    
});

Route::post('registerUser',[api::class,'RegisterUser']);
Route::post('loginUser',[api::class,'loginUser']);

