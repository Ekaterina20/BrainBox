<?php

Route::get('/', function () { return view('welcome'); });

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function() {
    Route::get('/', function () { return view('admin.index'); });
    Route::get('/games', 'Admin\AdminController@gamesList');
    Route::get('/game/create', function () { return view('admin.game.create'); });
    Route::post('/game/create', 'Admin\AdminController@createGame');
    Route::get('/game/{id}/edit', 'Admin\AdminController@gameDetails');
    Route::put('/game/{id}/finish', 'Admin\AdminController@gameFinish');
    Route::put('/game/{id}/remove', 'Admin\AdminController@removeGame');
    Route::post('/game/edit', 'Admin\AdminController@editGame');
    Route::post('/level/create', 'Admin\AdminController@createLevel');
    Route::put('/level/{id}/remove', 'Admin\AdminController@removeLevel');
    Route::get('/level/{id}/edit', 'Admin\AdminController@levelDetails');
    Route::post('/level/edit', 'Admin\AdminController@editLevel');
    Route::post('/attach/create', 'Admin\AdminController@createAttach');
    Route::put('/attach/{id}/remove', 'Admin\AdminController@removeAttach');
    Route::get('/attach/{id}/edit', 'Admin\AdminController@attachDetails');
    Route::post('/attach/edit', 'Admin\AdminController@editAttach');
    Route::post('/answer/create', 'Admin\AdminController@createAnswer');
    Route::put('/answer/{id}/remove', 'Admin\AdminController@removeAnswer');
    Route::get('/answer/{id}/edit', 'Admin\AdminController@answerDetails');
    Route::post('/answer/edit', 'Admin\AdminController@editAnswer');
    Route::post('/help/create', 'Admin\AdminController@createHelp');
    Route::put('/help/{id}/remove', 'Admin\AdminController@removeHelp');
    Route::get('/help/{id}/edit', 'Admin\AdminController@helpDetails');
    Route::post('/help/edit', 'Admin\AdminController@editHelp');
});

Route::group(['prefix' => 'admin', 'middleware' => 'guest'], function() {
    Route::get('/login', function () { return view('admin.login'); });
    Route::post('/login', 'Admin\AdminController@login');
});
