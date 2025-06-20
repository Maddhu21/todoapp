<?php

use App\Http\Controllers\AuthManager;
use App\Http\Controllers\TaskManager;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("login", [AuthManager::class, "login"])->name("login");
Route::post("login", [AuthManager::class, "loginPost"])->name("login.post");
Route::get("register", [AuthManager::class, "register"])->name("register");
Route::post("register", [AuthManager::class, "registerPost"])->name("register.post");
Route::get("logout", [AuthManager::class, "logout"])->name("logout");


Route::middleware("auth")->group(function () {
    Route::get('/', [TaskManager::class, "listTask"])->name("home");

    // Task
    Route::get("task/add", [TaskManager::class, "addTask"])->name("task.add");
    Route::post("task/add", [TaskManager::class, "addTaskPost"])->name("task.add.post");
    Route::get("task/update/{id}/{status_id}", [TaskManager::class, "updateTaskStatus"])->name("task.status.update");
    Route::get("task/{id}", [TaskManager::class, "getInfo"])->name("task.show");
    Route::put('/task/update/{id}', [TaskManager::class, 'update'])->name('task.update');
    Route::delete("task/delete/{id}", [TaskManager::class, "deleteTask"])->name("task.delete");

    //Master settings
    Route::resource('masters', MasterController::class);
    Route::post('/masters/data', [MasterController::class, 'getTableData'])->name('masters.data');
    Route::post('/masters/fetch', [MasterController::class, 'fetchRecord'])->name('masters.edit.fetch');

    //User Profile
    Route::resource('profiles', ProfileController::class);

});
