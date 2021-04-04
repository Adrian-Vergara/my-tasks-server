<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Task\TaskController;

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

$admin_scope = config('app.roles.admin');
$operator_scope = config('app.roles.operator');

Route::post('v1/authenticate', array(AuthController::class, 'authenticate'));

Route::group(['prefix' => 'v1'], function () use ($admin_scope, $operator_scope) {

    Route::apiResource('roles', RoleController::class)->only('index')->middleware("scope:{$admin_scope}");

    Route::put('users/change-password', array(UserController::class, 'updatePassword'))->middleware("scope:{$admin_scope},{$operator_scope}");
    Route::put('users/{id}/enable-disable', array(UserController::class, 'enableAndDisable'))->middleware("scope:{$admin_scope}");
    Route::apiResource('users', UserController::class)->only('index', 'store')->middleware("scope:{$admin_scope}");


    Route::get('projects/{id}/tasks', array(TaskController::class, 'getAllByProject'))->middleware("scope:{$admin_scope},{$operator_scope}");
    Route::post('projects/{id}/tasks', array(TaskController::class, 'store'))->middleware("scope:{$admin_scope},{$operator_scope}");
    Route::put('tasks/{id}/finish', array(TaskController::class, 'finishTask'))->middleware("scope:{$admin_scope},{$operator_scope}");
    Route::apiResource('tasks', TaskController::class)->only('show', 'update', 'destroy')->middleware("scope:{$admin_scope},{$operator_scope}");

    Route::put('projects/{id}/finish', array(ProjectController::class, 'finishProject'))->middleware("scope:{$admin_scope},{$operator_scope}");
    Route::apiResource('projects', ProjectController::class)->middleware("scope:{$admin_scope},{$operator_scope}");

});

Route::get('error', array(AuthController::class, 'error'))->name('login');
