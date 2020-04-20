<?php

namespace Neliserp\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class HasPermission
{
    public function handle($request, Closure $next)
    {
        $route_name = $this->getRouteName();

        list($table, $method) = explode('.', $route_name);

        $model_name = Str::of($table)->singular()->studly();

        $policy_method = $this->getPolicyMethod($method);

        $package_name = $this->getPackageName();

        $policy_class = "{$package_name}Policies\\{$model_name}Policy";

        if (! class_exists($policy_class)) {
            abort(403, "Unauthorized action. HasPermission has no class '{$policy_class}'");
        }

        $policy = new $policy_class;

        // TODO: Policy class should implements some interface to force 'read', 'write' exists
        //if (! method_exists($policy, $policy_method)) {
        //    abort(403, "Unauthorized action. HasPermission has no method '{$policy_method}'");
        //}

        if (! $policy->$policy_method(auth()->user())) {
            abort(403, "Unauthorized action. HasPermission has no permission '{$policy_method}'");
        }

        return $next($request);
    }

    protected function getPolicyMethod($method)
    {
        switch ($method) {
            case 'index':
            case 'show':
                return 'read';

            case 'store':
            case 'update':
            case 'destroy':
                return 'write';

            default:
                abort(403, "Unauthorized action. HasPermission get invalid method '{$method}'.");
                break;
        }
    }

    protected function getRouteName()
    {
        // route_name should be in format "items.index"
        $route_name = Route::currentRouteName();

        if ($route_name == '') {
            abort(403, 'Unauthorized action. HasPermission get no route name defined.');
        }

        if (strpos($route_name, '.') === false) {
            abort(403, "Unauthorized action. HasPermission get invalid route name '{$route_name}'.");
        }

        return $route_name;
    }

    protected function getPackageName()
    {
        $route_action = request()->route()->getAction();

        if (! isset($route_action['namespace'])) {
            abort(403, 'Unauthorized action. HasPermission get no namespace.');
        }

        $namespace_name = $route_action['namespace'];   // Neliserp\Core\Http\Controllers
        $package_name = str_replace('Http\\Controllers', '', $namespace_name);

        return $package_name;
    }
}
