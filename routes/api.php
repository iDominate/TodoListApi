<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Label\LabelController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\TodoList\TodoListController;

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

Route::group(["prefix" => "user"], function () {
    Route::post("register", [UserController::class, "register"])->name("user.register");
    Route::post("login", [UserController::class, "login"])->name("user.login");
    Route::get("profile", [UserController::class, "profile"])->name("user.profile")->middleware("auth:sanctum");
    Route::post("logout", [UserController::class, "logout"])->name("user.logout")->middleware("auth:sanctum");
});

Route::group(["prefix" => "admin"], function () {
    Route::post("login", [AdminController::class, "login"])->name('admin.login');
    Route::post("logout", [AdminController::class, "logout"])->name('admin.logout');
});

Route::group(["middleware" => "auth:sanctum"], function () {
    Route::apiResource("todos", TodoListController::class);
    Route::apiResource('todos.tasks', TaskController::class)->shallow();
    Route::apiResource("tasks.labels", LabelController::class)->shallow();
});


