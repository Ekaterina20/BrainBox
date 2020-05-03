<?php

//======================================================================
// Auth
//======================================================================

Route::group(['prefix' => 'auth'], function() {
  Route::post('/register', 'API\AuthController@register');
  Route::post('/login', 'API\AuthController@login');
});

//======================================================================
// Team
//======================================================================

Route::group(['prefix' => 'team', 'middleware' => 'auth:api'], function() {

  Route::post('/invite/cancel', 'API\TeamsController@cancelInvite');

  Route::post('/request/reject', 'API\TeamsController@rejectRequest');
  Route::post('/request/accept', 'API\TeamsController@acceptRequest');

  Route::put('/update', 'API\TeamsController@update');
  Route::get('/profile', 'API\TeamsController@profile');
  Route::get('/details', 'API\TeamsController@details');
  Route::get('/list', 'API\TeamsController@list');
	Route::post('/search', 'API\TeamsController@search');
  Route::post('/create', 'API\TeamsController@create');
  Route::post('/kick', 'API\TeamsController@kickUser');
  Route::post('/invite', 'API\TeamsController@inviteUsers');
  Route::post('/leave', 'API\TeamsController@leave');
});

//======================================================================
// User
//======================================================================

Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function() {

  Route::get('/details', 'API\UsersController@details');
  Route::get('/profile', 'API\UsersController@profile');
  Route::get('/inbox', 'API\UsersController@inbox');
  Route::post('/edit', 'API\UsersController@edit');
  Route::post('/edit/avatar', 'API\UsersController@editAvatar');
  Route::get('/list', 'API\UsersController@list');
  Route::post('/search', 'API\UsersController@search');

  // Team Requests
  Route::post('/request/send', 'API\UsersController@sendRequest');
  Route::post('/request/cancel', 'API\UsersController@cancelRequest');

  // Team Invites
  Route::post('/invite/reject', 'API\UsersController@rejectInvite');
  Route::post('/invite/accept', 'API\UsersController@acceptInvite');
});

//======================================================================
// Game
//======================================================================

Route::group(['prefix' => 'game', 'middleware' => 'auth:api'], function() {
  //-----------------------------------------------------
  // Заявки в игру
  //-----------------------------------------------------
  Route::post('/request/send', 'API\GamesController@sendGameRequest');
  //-----------------------------------------------------
  // Базовые ендпоинты
  //-----------------------------------------------------
  Route::get('/details', 'API\GamesController@details');
  Route::get('/list', 'API\GamesController@list');
  Route::post('/search', 'API\GamesController@search');
  //-----------------------------------------------------
  // Процесс игры
  //-----------------------------------------------------
  Route::get('/active', 'API\GamesController@active');
  Route::get('/level', 'API\GamesController@curLevel');
  Route::get('/help', 'API\GamesController@getHelp');
  Route::post('/level/attempt', 'API\GamesController@tryAnswer');
  Route::post('/level/jump', 'API\GamesController@jumpLevel');
  Route::get('/stats', 'API\GamesController@gameStatistic');
  Route::get('/levels', 'API\GamesController@gameLevels');
});

//======================================================================

if (!env('APP_DEBUG')) {
  Route::fallback(function(){
    return response()->json([
      'message' => 'Not Found.'], 404);
  });
}
