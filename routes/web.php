<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\boardController;
use App\Http\Controllers\taskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route that can be accessed without authentication
Route::middleware('guest')->group(function(){
    Route::get('/',[userController::class,'home'])->name('home');
    Route::get('login',[userController::class,'login'])->name('login');
    Route::post('login',[userController::class,'loginUser'])->name('loginUser');
    Route::get('register',[userController::class,'register'])->name('register');
    Route::post('register',[userController::class,'registerUser'])->name('registerUser');

});

//Route that can be accessed only with authentication
Route::middleware('auth')->group(function(){
    Route::get('logout',[userController::class,'logout'])->name('logout');
    Route::get('dashboard',[boardController::class,'dashboard'])->name('dashboard');

    //Boards Routes
    Route::get('board',[boardController::class,'viewBoards'])->name('boards');
    Route::get('editBoard/{id?}',[boardController::class,'editBoard'])->name('editBoard');
    Route::post('saveBoard',[boardController::class,'saveBoard'])->name('saveBoard');
    Route::post('deleteBoard',[boardController::class,'deleteBoard'])->name('deleteBoard');

    //Task Routes
    Route::get('task/{id?}',[taskController::class,'viewTasks'])->name('tasks');
    Route::get('editTask/{boardId}/{taskId?}',[taskController::class,'editTask'])->name('editTask');
    Route::post('saveTask',[taskController::class,'saveTask'])->name('saveTask');
    Route::post('changeStatus',[taskController::class,'changeStatus'])->name('changeStatus');
    Route::post('deleteTask',[taskController::class,'deleteTask'])->name('deleteTask');


});