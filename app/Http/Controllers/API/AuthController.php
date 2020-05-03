<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Broadcast;

class AuthController extends Controller
{

	public function register(Request $request)
	{
		$this->validate($request, [
			'name' => ['required', 'max:100'],
			'phone' => ['required', 'unique:users'],
			'password' => ['required'],
		]);

		return response()
      ->json(User::find(User::add($request->all()))->details());
	}

	public function login(Request $request)
	{
		$this->validate($request, [
			'phone' => ['required'],
			'password' => ['required'],
		]);

    return Auth::attempt($request->all()) ?
      response()->json(Auth::user()->details()) :
      response()->json(['message' => __('auth.failed')], 401);
	}
}