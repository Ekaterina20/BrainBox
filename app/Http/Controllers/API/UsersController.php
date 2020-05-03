<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\TeamRequest;

class UsersController extends Controller
{
  public function details(Request $request)
  {
    return response()->json($request->user()->details());
  }

  public function profile(Request $request)
  {
    $this->validate($request, [
      'id' => ['required', 'integer', 'exists:users']
    ]);

    return response()->json(User::find($request->get('id'))->profile());
  }

	public function list(Request $request)
	{
		return response()
      ->json(User::list());
	}

	public function search(Request $request)
	{
    $this->validate($request, [
      'query' => ['required']
    ]);

		return response()
      ->json(User::search($request->get('query')));
	}

  public function sendRequest(Request $request)
  {
    $this->validate($request, [
      'team_id' => ['required', 'integer', 'exists:teams,id']
    ]);

    $user = $request->user();
    $id = $request->input('team_id');

    return $user->requests()
      ->where([['team_id', $id], ['status', 0]])
      ->exists() ?
        response()->json(['message' => 'Заявка на рассмотрении'], 422) :
        response()->json($user->requests()->create(['team_id' => $id]));
  }

  public function cancelRequest(Request $request)
  {
    $this->validate($request, [
      'id' => ['required', 'integer', 'exists:team_requests']
    ]);

    $user = $request->user();
    $id = $request->input('id');

    $trequest = $user->requests()
      ->where([
        ['status', array_flip(TeamRequest::STATUS)['pending']],
        ['id', $id]
      ])
      ->first();

    return $trequest ?
      response()->json($trequest->cancel()) :
      response()->json(['message' => 'Невозможно отменить заявку'], 422);
  }

  public function inbox(Request $request)
  {
    $user = $request->user();

    return response()->json([
      'requests' => $user->requests,
      'invites' => $user->invites
    ]);
  }

  public function rejectInvite(Request $request)
  {
    $this->validate($request, [
      'id' => ['required', 'integer', 'exists:team_invites']
    ]);

    $user = $request->user();
    $id = $request->input('id');

    $tinvite = $user->invites()
      ->where([
        ['status', array_flip(TeamRequest::STATUS)['pending']],
        ['id', $id]
      ])
      ->first();

    return $tinvite ?
      response()->json($tinvite->reject()) :
      response()->json(['message' => 'Невозможно отклонить приглашение'], 422);
  }

  public function acceptInvite(Request $request)
  {
    $this->validate($request, [
      'id' => ['required', 'integer', 'exists:team_invites']
    ]);

    $user = $request->user();
    $id = $request->input('id');

    $tinvite = $user->invites()
      ->where([
        ['status', array_flip(TeamRequest::STATUS)['pending']],
        ['id', $id]
      ])
      ->first();

    if (!$tinvite) {
      return response()->json(['message' => 'Невозможно принять приглашение'], 422);
    } else if ($user->team_id) {
      return response()->json(['message' => 'Сначала выйдите из команды'], 422);
    }
    
    $user->team_id = $tinvite->team_id;
    $user->save();

    return response()->json($tinvite->accept());  
  }

  public function edit(Request $request)
  {
    $this->validate($request, [
      'name' => ['string', 'max:100'],
      'phone' => ['unique:users', 'numeric'],
      'email' => ['unique:users']
    ]);

    $user = $request->user();
    $user->fill($request->all());
    $user->save();

    return response()->json($user->details());
  }

  public function editAvatar(Request $request)
  {
    $this->validate($request, [
      'avatar' => ['image', 'required']
    ]);

    return response()
    ->json($request->user()
      ->updateAvatar($request->file('avatar')));
  }

  //-----------------------------------------------------
  // Old
  //-----------------------------------------------------

  public function team(Request $request)
  {
    return response()->json($request->user()->team);
  }
}
