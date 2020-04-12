<?php

namespace Neliserp\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Neliserp\Core\Role;
use Neliserp\Core\Filters\RoleFilter;
use Neliserp\Core\Http\Requests\RoleRequest;
use Neliserp\Core\Http\Resources\RoleResource;

class RoleController extends Controller
{
    protected $per_page;

    public function __construct()
    {
        $this->per_page = request('per_page', 10);
    }

    public function index()
    {
        $roles = Role::filter(new RoleFilter())
            ->paginate($this->per_page);

        return RoleResource::collection($roles);
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);

        return new RoleResource($role);
    }

    public function store(RoleRequest $request)
    {
        $data = $request->toArray();

        $role = Role::create($data);

        return new RoleResource($role);
    }

    public function update(RoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);

        $updated = $role->update($request->toArray());

        return new RoleResource($role);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        $deleted = $role->delete();

        return response([], 200);
    }
}
