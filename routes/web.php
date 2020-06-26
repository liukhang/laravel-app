<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Route::get('/redirect/{social}', 'SocialAuthController@redirect');
Route::get('/callback/{social}', 'SocialAuthController@callback');
Route::group(['middleware' => 'auth'], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/user', 'DashboardController@getUser');
    Route::get('/user/load-more', 'DashboardController@loadUser')->name('load-more');
    Route::post('/user/clone-repo', 'DashboardController@repo')->name('clone-repo');
    Route::get('/listrepo', 'DashboardController@listRepo')->name('list-repo');
    Route::post('/forkrepo', 'DashboardController@forkRepo')->name('fork-repo');
});