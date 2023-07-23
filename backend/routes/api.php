<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;




Route::group(['middleware'=>['auth:sanctum']],function(){
    //admin
    Route::post('/add-admin',[AdminController::class, 'addAdmin']);
    Route::get('/admins',[AdminController::class, 'getAdmins']);
    Route::post('/add-company',[AdminController::class, 'addCompany']);
    Route::get('/company',[AdminController::class, 'getCompanies']);
    Route::put('/company/{company}',[AdminController::class, 'updateCompany']);
    Route::delete('/company/{company}',[AdminController::class, 'destroyCompany']);
    Route::post('/invite/{company}',[AdminController::class,'invite']);
    Route::delete('/invite/{invitation}',[AdminController::class,'cancel']);
    Route::get('/history',[AdminController::class,'history']);

    //employee
    Route::put('/employee', [EmployeeController::class,'update']);

    //both
    Route::post('/logout',[LoginController::class,'logout']);
    Route::get('/user', function(Request $request) {
        return $request->user()->load('employee.company.employees')->load('invitations');
    });
});

Route::post('/login',[LoginController::class,'login']);
Route::get('/invitation/{invite}', [EmployeeController::class, 'accept'])->name('api.invitation.accept');
Route::post('/invitation/{invite}',[EmployeeController::class,'confirm']);
