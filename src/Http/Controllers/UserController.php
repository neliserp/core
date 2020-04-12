<?php

namespace Neliserp\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Neliserp\Core\User;
use Neliserp\Core\Filters\UserFilter;
use Neliserp\Core\Http\Requests\UserRequest;
use Neliserp\Core\Http\Resources\UserResource;

class UserController extends Controller
{
    protected $per_page;

    public function __construct()
    {
        $this->per_page = request('per_page', 10);
    }

    public function index()
    {
        $users = User::filter(new UserFilter())
            ->paginate($this->per_page);

        return UserResource::collection($users);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return new UserResource($user);
    }

    public function store(UserRequest $request)
    {
        $data = $request->toArray();

        $user = User::create($data);

        return new UserResource($user);
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $updated = $user->update($request->toArray());

        return new UserResource($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $deleted = $user->delete();

        return response([], 200);
    }
}
