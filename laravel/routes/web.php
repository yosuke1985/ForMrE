<?php

Auth::routes();

//Toppage
Route::get('/', 'ViewControllers\TopViewController@index');
Route::post('/', 'ViewControllers\TopViewController@post');

//AllTweet
Route::get('/AllTweet', 'ViewControllers\AllTweetViewController@index');
Route::post('/AllTweet', 'ViewControllers\AllTweetViewController@post');

//Social
Route::get('/users', 'ViewControllers\UsersListViewController@index');
Route::post('/users/unfollow', 'ViewControllers\UsersListViewController@unfollow');
Route::post('/users/follow', 'ViewControllers\UsersListViewController@follow');

