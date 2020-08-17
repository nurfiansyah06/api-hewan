<?php

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


//register dan login
Route::post('auth/register','API\AuthController@register')
    ->middleware('auth:api','superadmin' && 'admin'); //pembuatan akun admin dan petugas
Route::post('auth/login','API\AuthController@login'); //login untuk petugas
Route::post('auth/loginadmin','API\AuthController@loginadmin'); //login untuk admin dan superadmin


//untuk admin and superadmin
Route::get('/users','API\UserController@users')
    ->middleware('auth:api','superadmin'); //mengambil data petugas dan admin yang hanya bisa diakses superadmin
Route::get('/usersadmin','API\UserController@usersAdmin')
    ->middleware('auth:api','superadmin' && 'admin'); //mengambil data yang hanya admin
Route::get('/userspetugas','API\UserController@usersPetugas')
    ->middleware('auth:api');

//untuk profile
Route::get('/users/profile','API\UserController@profile')
    ->middleware('auth:api'); //mengambil data profile diri sendiri
Route::get('/users/{id}','API\UserController@profileById')
    ->middleware('auth:api','superadmin' && 'admin');  //mengambil data profile admin dan petugas
Route::put('/users/profilesuperadmin','API\UserController@updateprofileSuperAdmin')
    ->middleware('auth:api','superadmin'); //update profile superadmin
Route::put('/users/profile','API\UserController@updateprofile')
    ->middleware('auth:api'); //update profile sendiri
Route::put('/users/{id}','API\UserController@updateprofileById')
    ->middleware('auth:api','superadmin' && 'admin'); //update profile digunakan oleh admin dan superadmin

//untuk animal
Route::get('/animal','API\AnimalController@getDataByApprove')
    ->middleware('auth:api'); ///mendapatkan data animal yang sudah disetujui
Route::post('/animal','API\AnimalController@add')
    ->middleware('auth:api'); //penambahan form animal
Route::put('/animal/{id}','API\AnimalController@updateAnimalByPetugas')
    ->middleware('auth:api'); //update form animal milik petugas sendiri
Route::put('/animal/isapproval/{id}','API\AnimalController@setApproval')
    ->middleware('auth:api','superadmin' && 'admin'); //set approval
Route::delete('/animal/{id}','API\AnimalController@deleteAnimal')
    ->middleware('auth:api','superadmin' && 'admin'); //delete form animal hanya untuk admin dan superadmin



Route::delete('/users/{id}','API\UserController@deleteBySuperAdmin')
    ->middleware('auth:api','superadmin');//delete yang digunakan oleh superadmin
Route::delete('/users/{id}','API\UserController@deleteByAdmin')
    ->middleware('auth:api' ,'superadmin' && 'admin');//delete yang digunakan oleh admin

