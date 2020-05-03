<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Team;
use App\User;
use App\GameRequest;

class TeamsController extends Controller
{
  public function list()
  {
    return response()->json(Team::list());
  }

  public function search(Request $request)
  {
    $this->validate($request, [
      'query' => ['required']
    ]);

    return response()
      ->json(Team::search($request->get('query')));
  }

  public function create(Request $request)
  {
    $this->validate($request, [
      'name' => ['required', 'string', 'max:100', 'unique:teams'],
      'users' => ['array', 'max:'.Team::SIZE['max']]
    ]);

    $user = $request->user();

    if ($user->team_id) {
      return response()->json(['message' => 'Сначала выйдите из команды',], 422);
    }

    $team = Team::create(['name' => $request->input('name'), 'user_id' => $user->id]);

    $user->team_id = $team->id;
    $user->save();

    if ($users = $request->input('users')) $team->inviteUsers($users);

    return response()->json($team);
  }

  public function profile(Request $request)
  {
    $this->validate($request, [
      'id' => ['required', 'integer', 'exists:teams'],
    ]);

    return response()
      ->json(Team::find($request->get('id'))->profile());
  }

  public function details(Request $request)
  {
    $user = $request->user();

    if ($user->id != $user->team->user_id) {
      return response()->json(['message' => 'Отказанно в доступе'], 422);
    }

    return response()->json($user->team->details());
  }

  public function update(Request $request)
  {
    $this->validate($request, [
      'name' => ['required']
    ]);

    $user = $request->user();
    $team = $user->team;

    if ($user->team_id != $team->user_id) {
      return response()->json(['message' => 'Отказанно в доступе'], 422);
    }

    $team->update($request->all());

    return response()->json($team);
  }

  public function kickUser(Request $request)
  {
    $this->validate($request, [
      'user_id' => ['required', 'exists:users,id']
    ]);

    $cur_user = $request->user();
    $id = $request->input('user_id');
    $team = $cur_user->team;
    $user = $team->users()->where('id', $id)->first();

    if ($cur_user->team_id != $team->user_id) {
      return response()->json(['message' => 'Отказанно в доступе'], 422);
    } else if ($cur_user->id == $id) {
      return response()->json(['message' => 'Сначала передайте права капитана'], 422);
    } else if (!$user) {
      return response()->json(['message' => 'Не найденно'], 404);
    }

    $user->team_id = null;
    $user->save();

    return response()->json($user);
  }

  public function inviteUsers(Request $request)
  {
    $this->validate($request, [
      'ids' => ['required', 'array', 'min:1']
    ]);

    $user = $request->user();
    $team = $user->team;

    if ($user->id != $team->user_id) {
      return response()->json(['message' => 'Приглашать в команду может только капитан'], 422);
    }

    $ids = $request->input('ids');
    $ids = User::whereIn('id', $ids)->pluck('id');

    $team->inviteUsers($ids);

    return response()->json($team->invites);
  }

  public function acceptRequest(Request $request)
  {
    $this->validate($request, [
      'id' => ['required', 'integer', 'exists:team_requests'],
    ]);

    $user = $request->user();
    $team = $user->team;
    $id = $request->input('id');

    if ($user->id != $team->user_id) {
      return response()->json(['message' => 'Отказанно в доступе'], 422);
    }

    $request = $team->requests()->where('id', $id)->first();

    if (!$request) {
      return response()->json(['message' => 'Заявка не найденна'], 404);
    }

    $ruser = $request->user;

    $ruser->team_id = $request->team_id;
    $ruser->save();
    $ruser->requests()->delete();

    return response()->json($ruser);
  }

  public function rejectRequest(Request $request)
  {
    $this->validate($request, [
      'id' => ['required', 'integer', 'exists:team_requests'],
    ]);

    $user = $request->user();
    $team = $user->team;
    $id = $request->input('id');

    if ($user->id != $team->user_id) {
      return response()->json(['message' => 'Отказанно в доступе'], 422);
    }

    $request = $team->requests()->where('id', $id)->first();

    if (!$request) {
      return response()->json(['message' => 'Заявка не найденна'], 404);
    }

    return response()->json($request->reject());

  }

  public function cancelInvite(Request $request)
  {
    $this->validate($request, [
      'id' => ['required', 'integer', 'exists:team_invites'],
    ]);

    $user = $request->user();
    $team = $user->team;
    $id = $request->input('id');

    if ($user->id != $team->user_id) {
      return response()->json(['message' => 'Отказанно в доступе'], 422);
    }

    $invite = $team->invites()->where('id', $id)->first();

    if (!$invite) {
      return response()->json(['message' => 'Не найденно'], 404);
    }

    return response()->json($invite->cancel());
  }

  //-----------------------------------------------------
  // Old
  //-----------------------------------------------------

  public function join(Request $request)
  {
    $this->validate($request, [
      'id' => ['required', 'integer'],
    ]);

    $user = $request->user();

    if($user->team_status == User::$team_stats['requested']) {
      return response()->json([
        'success' => false,
        'message' => 'Заявка уже отправленна!'
      ], 422);
    } else if ($user->team_status == User::$team_stats['in_team']) {
      return response()->json([
        'success' => false,
        'message' => 'Cостоять можно только в одной команде!'
      ], 422);
    }

    if ($team = Team::find($request->get('id'))) {
      $user->joinTeam($team);
      return response()->json([
        'success' => true
      ]);
    } else {
     return response()->json([
      'message' => 'Нет такой команды!'
     ], 404);
    }
  }

  public function leave(Request $request)
  {
    return response()->json($request->user()->leaveTeam());
  }

  public function futureSessions(Request $request)
  {
    $this->validate($request, [
      'id' => ['required', 'integer'],
    ]);

    $team = Team::find($request->get('id'));

    return response()->json($team->future_sessions);
  }

  public function oldSessions(Request $request)
  {
    $this->validate($request, [
      'id' => ['required', 'integer'],
    ]);

    $team = Team::find($request->get('id'));

    return response()->json($team->old_sessions);
  }

  public function sendGameRequest(Request $request)
  {
    $this->validate($request, [
      'id' => ['required', 'integer', 'exists:game_sessions']
    ]);

    $gs_id = $request->input('id');
    $user = $request->user();

    if (GameRequest::where([
      'game_session_id' => $gs_id,
      'team_id' => $user->team_id
    ])->get()->isNotEmpty()) {
      return response()->json(['message' => 'Заявка на рассмотрении'], 422);
    }

    if ($user->isLeader()) {
      GameRequest::create([
        'game_session_id' => $gs_id,
        'team_id' => $user->team_id
      ]);
      return response()->json(['success' => true]);
    }

    return response()->json(['message' => 'Заявки на участие могут отправлять только капитаны команд'], 422);
  }

  public function rename(Request $request)
  {
    $this->validate($request, [
      'name' => ['required', 'string']
    ]);

    $user = $request->user();

    if ($user->id != $user->team->user_id) {
      return response()->json(['message' => 'Дейсвтие доступно только капитану команды'], 422);
    }

    $team = $user->team;
    $team->fill($request->all());
    $team->save();

    return response()->json(['success' => true]);
  }
}