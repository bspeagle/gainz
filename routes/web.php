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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/contest', 'contestController@index')->middleware('auth');

Route::post('/contest', ['uses' => 'contestController@createContest'])->name('createContest')->middleware('auth');

Route::get('/contest/{id}', ['uses' => 'contestController@getContest'])->name('getContest')->middleware('auth');

Route::get('/contest/{id}/users', ['uses' => 'contestUsersController@getUsers'])->name('manageUsers')->middleware('auth');

Route::post('/email/sendInvite', ['uses' => 'contestUsersController@inviteUser'])->name('sendInvite')->middleware('auth');

Route::get('/invite/{inviteUUID}', ['uses' => 'inviteController@startProcess'])->name('startInviteProcess')->middleware('auth');

Route::post('/invite/response', ['uses' => 'inviteController@respondInvite'])->name('respondInvite')->middleware('auth');

Route::post('/users/remove', ['uses' => 'contestUsersController@removeUser'])->name('removeContestUser')->middleware('auth');

Route::post('/invite/revokeInvite', ['uses' => 'inviteController@revokeInvite'])->name('revokeInvite')->middleware('auth');