<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => '/auth', ['middleware' => 'throttle:20,5']], function() {
    Route::post('/register','Auth\RegisterController@register');
    Route::post('/login','Auth\LoginController@login');
});

Route::group(['middleware' => 'jwt.auth'], function() {
    Route::get('/me','MeController@index');
    Route::get('/auth/logout','MeController@logout');
    Route::get('/auth/test','TestController@index');

    Route::post('/articles/store','ArticleController@store');
    Route::post('/articles/remove', 'ArticleController@remove');
    Route::post('/articles/like', 'ArticleController@like');
    Route::post('/articles/comment','ArticleController@comment');
    Route::post('/articles/comments','ArticleController@commentsAll');

    Route::post('/user/updateAvatar','UserController@updateAvatar');
    Route::post('/user/likeAvatar','UserController@likeAvatar');

    Route::post('/user/profile','UserController@profile');

    Route::post('/user/addFriend','UserController@addFriend');
    Route::post('/user/removeFriend','UserController@removeFriend');

    Route::post('/user/friends','UserController@getFriends');
    Route::post('/user/friendRequests','UserController@getFriendRequests');
    Route::post('/user/confirmFriend','UserController@confirmFriend');

    Route::post('/message/sendMessage','MessageController@sendMessage');
    Route::post('/message/getMessages','MessageController@getMessages');
    Route::post('/message/getUserMessages','MessageController@getUserMessages');
});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
