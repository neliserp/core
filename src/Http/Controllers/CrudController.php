<?php

namespace Neliserp\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

abstract class CrudController extends Controller
{
    /**
     * Model class name.
     *
     * @var string
     */
    protected $model;

    /**
     * Filter class name.
     *
     * @var string
     */
    protected $filter;

    /**
     * HTTP form request class name.
     *
     * @var string
     */
    protected $request;

    /**
     * HTTP resource class name.
     *
     * @var string
     */
    protected $resource;

    public function __construct()
    {
        $this->initClassProperties();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Http\Resources\Json\JsonResource
     */
    public function index()
    {
        $per_page = request('per_page', 10);

        $items = $this->model::filter(new $this->filter())
            ->paginate($per_page);

        return $this->resource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return Illuminate\Http\Resources\Json\JsonResource
     */
    public function show($id)
    {
        $item = $this->model::findOrFail($id);

        return new $this->resource($item);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(Request $request)
    {
        $validator = $this->validate($request);
        $item = $this->model::create($validator);

        return new $this->resource($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return Illuminate\Http\Resources\Json\JsonResource
     */
    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        $validator = $this->validate($request);
        $updated = $item->update($validator);

        return new $this->resource($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = $this->model::findOrFail($id);
        $deleted = $item->delete();

        return response(null, 200);
    }

    protected function initClassProperties()
    {
        $reflection = new \ReflectionClass($this);
        $namespace_name = $reflection->getNamespaceName();  // Neliserp\Core\Http\Controllers
        $short_class_name = $reflection->getShortName();

        $package_name = str_replace('\Http\Controllers', '', $namespace_name);
        $model_name = str_replace('Controller', '', $short_class_name);

        $this->model = "{$package_name}\\{$model_name}";
        $this->filter = "{$package_name}\\Filters\\{$model_name}Filter";
        $this->request  = "{$package_name}\\Http\\Requests\\{$model_name}Request";
        $this->resource = "{$package_name}\\Http\\Resources\\{$model_name}Resource";
    }

    protected function validate(Request $request)
    {
        return $validator = Validator::make(
            $request->all(),
            (new $this->request($request->all()))->rules()
        )->validate();
    }
}
