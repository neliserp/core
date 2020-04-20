# Neliserp Core Package

## Installation

### Composer packages

```
composer require laravel/sanctum

composer require laravel/ui
```

### Config sanctum

Add `EnsureFrontendRequestsAreStateful` in `api` middleware groups
```php
// app/Http/Kernel.php

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class Kernel extends HttpKernel
{
    // ...
    protected $middlewareGroups = [
        // ...
        'api' => [
            EnsureFrontendRequestsAreStateful::class,
            // ...
```

Add your domain in `.env`

```
SANCTUM_STATEFUL_DOMAINS=your-domain.test
SESSION_DOMAIN=.your-domain.test
```

### Middleware

Register `has_permission` in `app/Http/Kernel.php`

```php
// app/Http/Kernel.php
class Kernel extends HttpKernel
{
    // ...
    protected $routeMiddleware = [
        // ...
        'has_permission' => \Neliserp\Core\Http\Middleware\HasPermission::class,
    ];
```

### Remove Default redirect

```php
// app/Providers/RouteServiceProvider.php

class RouteServiceProvider extends ServiceProvider
    // ...
    public const HOME = '';
```
