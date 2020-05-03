<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Game;
use App\GameRequest;
use App\GameResult;
use App\Help;
use App\Level;


class GamesController extends Controller
{
  public function list()
  {
    return response()->json(Game::list());
  }

  public function search(Request $request)
  {
    $this->validate($request, [
      'query' => ['required']
    ]);

    return response()->json(Game::search($request->input('query')));
  }

  public function details(Request $request)
  {
    $this->validate($request, [
      'id' => ['required', 'integer', 'exists:games']
    ]);

    return response()
      ->json(Game::find($request->input('id'))->details());
  }

  public function sendGameRequest(Request $request)
  {
    $this->validate($request, [
      'game_id' => ['required', 'integer', 'exists:games,id']
    ]);

    $user = $request->user();
    $id = $request->input('game_id');

    $result = GameResult::where([
      ['game_id', $id],
      ['team_id', $user->team_id]
    ])->first();

    if (!$result) {
      GameResult::create([
        'game_id' => $id,
        'team_id' => $user->team_id
      ]);

      return response()->json(['message' => 'Заявка принята']);
    }

    return response()->json(['message' => 'Вы уже участвуете в игре'], 422);

    /*
    * OLD
    */
    /*
    $grequest = GameRequest::where([
      ['game_id', $id],
      ['team_id', $user->team_id],
      ['status', 0]
    ])->exists();

    if ($grequest) {
      return response()->json(['message' => 'Заявка на рассмотрении'], 422);
    }

    return response()->json($user->team->sendGameRequest($id));
    */
  }

  public function active(Request $request)
  {
    $game = $request->user()->team->game;
    return $game ?
      response()->json($game->state()) :
      response()->json(['message' => 'Нет активных игр'], 404);
  }

  public function curLevel(Request $request)
  {
    $this->validate($request, ['level' => ['integer', 'nullable']]);

    $n = $request->input('level');

    if ($game = $request->user()->team->game) {
      return response()->json([
        'level' => $n ? $game->getLevelN($n) : $game->getCurrentLevel(),
        'finish' => $game->isFinished(),
        'timer' => $game->timer
      ]);
    }
    return response()->json(['message' => 'Нет активных игр'], 404);
  }

  public function getHelp(Request $request)
  {
    $this->validate($request, [
      'id' => ['required', 'integer', 'exists:helps']
    ]);

    return response()->json(Help::find($request->input('id')));
  }

  public function tryAnswer(Request $request)
  {
    $this->validate($request, [
      'id' => ['required', 'integer', 'exists:levels'],
      'code' => ['required']
    ]);

    $code = $request->input('code');
    $level = Level::find($request->input('id'));

    $answer = $level->answers()->where('code', $code)->first();

    if (!$answer) {
      return response()->json(['message' => 'Неверный ответ'], 422);
    }

    $game = $level->game;

    if ($level->hasAttempt($code)) {
      return response()->json([
        'answered' => $level->answered,
        'finish' => $game->isFinished()
      ]);
    }

    $level->makeAttempt($code);    

    if ($level->complete) {
      $game->jumpNextLevel();

      if ($game->isFinished()) $game->finish();
    }

    return response()->json([
      'answered' => $level->answered,
      'finish' => $game->isFinished()
    ]);
  }

  public function jumpLevel(Request $request)
  {
    $game = $request->user()->team->game;
    $level = $game->getCurrentLevel();

    if (!$game) return response()->json(['message' => 'Нет активных игр'], 404);

    if (!$level) {
      return response()->json([
        'level' => $level,
        'finish' => $game->isFinished()
      ]);
    }

    if (!$level->jump) {
      return response()->json([
        'message' => 'Этот уровень нельзя перескочить'
      ], 422);
    }

    if ($level->jump * 60  + $game->timer > 0) {
      return response()->json([
        'level' => $level,
        'finish' => $game->isFinished()
      ]);
    }

    $game->jumpNextLevel();
    if ($game->isFinished()) $game->finish();

    return response()->json([
      'level' => $game->getCurrentLevel(),
      'finish' => $game->isFinished()
    ]);
  }

  public function gameStatistic(Request $request)
  {
    $this->validate($request, ['levels' => 'boolean']);

    $game = $request->user()->team->game;

    if (!$game) response()->json(['message' => 'Нет активных игр'], 404);
    
    if ($request->input('levels')) {
      return response()->json([
        'results' => $game->results()->orderBy('time')->get(),
        'levels' => $game->isStorm() ? $game->levels : null
      ]);
    } else {
      return response()->json($game->results()->orderBy('time')->get());
    }
  }

  public function gameLevels(Request $request)
  {
    $game = $request->user()->team->game;

    if (!$game) response()->json(['message' => 'Нет активных игр'], 404);

    return response()->json($game->levels);
  }

  public function getLevelsState(Request $request)
  {
    $game = $request->user()->team->game;

    if (!$game) response()->json(['message' => 'Нет активных игр'], 404);

    return response()->json($game->isStorm() ? $game->levels : null);
  }
}
