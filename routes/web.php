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
    return 'Hello';
});

//Auth::routes();

Route::post('/api/user', 'api\UserController@index')->name('user.index');

Route::post('/api/articles', 'api\ArticleController@index');

Route::post('/api/comments', 'api\CommentController@index');

//Route::post('/api/articles/create', 'api\ArticleController@store')->name('articles.store');