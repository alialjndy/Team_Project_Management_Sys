<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\admin\AssignProjectToUserController;
use App\Http\Controllers\admin\AssignRoleToUserOnTheSystemController;
use App\Http\Controllers\admin\CrudProjectController;
use App\Http\Controllers\admin\CrudUserController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\DeveloperAndTesterController;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\adminMiddleware;
use App\Http\Middleware\managerMiddleware;
use App\Http\Middleware\ProjectDeveloperMiddleware;
use App\Http\Middleware\ProjectManagerMiddleware;
use App\Http\Middleware\ProjectTesterMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Admin only (Admin on the System)
Route::middleware([adminMiddleware::class])->group(function(){
    //CRUD Users
    Route::apiResource('ManagementUser',CrudUserController::class);
    Route::post('forceDelete/{UserId}',[CrudUserController::class ,'forceDelete']);
    Route::post('RestoreUser/{UserId}',[CrudUserController::class ,'restore']);

    // CRUD Projects
    Route::apiResource('ManagementProject',CrudProjectController::class);
    Route::post('ManagementProject/forceDelete/{project_id}',[CrudProjectController::class, 'forceDelete']);
    Route::post('ManagementProject/RestoreProject/{project_id}',[CrudProjectController::class, 'restore']);

    //Assign (System Role) To User (Admin only)
    Route::post('Assign_System_Role',[AssignRoleToUserOnTheSystemController::class, 'AssignRoleToUserOnTheSystem']);


});

Route::middleware([managerMiddleware::class])->group(function (){

    //Assign Project to Manager in project (manager only on the system 'system level')
    Route::post('projects/{projectID}/assign-user',[AssignProjectToUserController::class, 'assign']);


});
//Get participant user in project (manager only)
Route::get('projects/{projectID}/users',[AssignProjectToUserController::class, 'getProjectUsers']);


// Crud Task in project (manager Role in project)
Route::middleware([ProjectManagerMiddleware::class])->group(function (){
    //
Route::prefix('project/{project_id}')->group(function () {
    Route::apiResource('tasks', TaskController::class);
});

    Route::post('project/{project_id}/AssignTaskToDeveloper',[DeveloperAndTesterController::class , 'AssignTaskToDeveloper']);

    Route::get('getLastTaskInProject/{project_id}',[CrudProjectController::class, 'getLastTaskRelatedWithProject']);
    Route::get('getOldTaskInProject/{project_id}',[CrudProjectController::class, 'getOldestTaskRelatedWithProject']);
    Route::get('getHighestPriority',[CrudProjectController::class, 'getHighestPriority']);

});

// Developer Route (change status of the task in projects who work in it)
Route::middleware([ProjectDeveloperMiddleware::class])->group(function (){
    Route::post('project/{project_id}/changeTaskStatus', [DeveloperAndTesterController::class,'changeStatusByDeveloper']);
});

// Tester Route (add note to Task in project who work in it)
Route::middleware([ProjectTesterMiddleware::class])->group(function(){
    Route::post('project/{project_id}/AddNote',[DeveloperAndTesterController::class , 'AddNote']);
});


// public Route
Route::apiResource('Activity',ActivityController::class);

Route::post('login',[AuthController::class , 'login']);
Route::post('logout',[AuthController::class , 'logout']);
Route::get('me',[AuthController::class ,'me']);
