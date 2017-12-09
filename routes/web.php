<?php

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

Route::get('/', function () {
    return view('welcome');
});

// Quick demo admin page
Route::get('/admin', function () {
    return view('admin/admin-wallet');
});

// Quick demo user page
Route::get('/user/{email}', function ($email) {
    return view('user/user-wallet', compact('email'));
});