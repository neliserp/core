<?php

namespace Neliserp\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Neliserp\Core\Permission;
use Neliserp\Core\Filters\PermissionFilter;
use Neliserp\Core\Http\Requests\PermissionRequest;
use Neliserp\Core\Http\Resources\PermissionResource;

class PermissionController extends Controller
{
    protected $per_page;

    public function __construct()
    {
        $this->per_page = request('per_page', 10);
    }

    public function index()
    {
        $permissions = Permission::filter(new PermissionFilter())
            ->paginate($this->per_page);

        return PermissionResource::collection($permissions);
    }

    public function show($id)
    {
        $permission = Permission::findOrFail($id);

        return new PermissionResource($permission);
    }

    public function store(PermissionRequest $request)
    {
        $data = $request->toArray();

        $permission = Permission::create($data);

        return new PermissionResource($permission);
    }

    public function update(PermissionRequest $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $updated = $permission->update($request->toArray());

        return new PermissionResource($permission);
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        $deleted = $permission->delete();

        return response([], 200);
    }
}
