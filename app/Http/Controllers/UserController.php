<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function index()
  {
    return UserResource::collection(User::all());
  }

  public function show($id)
  {
    $user = User::find($id);
    return new UserResource($user);
  }

  public function store(Request $request)
  {
    $requestData = $request->only([
      'name',
      'email',
      'password',
    ]);

    $requestData['password'] = bcrypt($requestData['password']);

    $user = User::create($requestData);
    return new UserResource($user);
  }

  public function update(Request $request, User $user)
  {
    $requestData = $request->only([
      'name',
      'email',
      'password',
    ]);

    $requestData['password'] = bcrypt($requestData['password']);

    $user->update($requestData);
    return new UserResource($user);
  }

  public function destroy(User $user)
  {
    if (User::count() == 1) {
      return response()->json(null, 403);
    }
    $user->delete();
    return response()->json(null, 204);
  }
}
