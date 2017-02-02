Laravel Multi Tenant Redis
============
By default Laravel's Redis cache store will empty the entire Redis database when running `php artisan cache:clear`. This can be a problem when you are running multiple applications on the same Redis instance. Laravel's "fix" for this is to change the Redis database for each application. This really isn't a solution as using different Redis databases aren't really recommended. 

That is where this package comes in. It is a drop in replacement for the Redis cache store. It keeps track of the keys added to cache so that only the keys for the application will be deleted on a cache flush.

Before installing it would be a good idea to clear the application cache.

### Installation
Add to your composer.json file
```json
"um-flint/laravel-multi-tenant-redis": "0.0.*"
```

### Register the package

In config/app.php add the service provider.

```php
UMFlint\Cache\MultiTenantRedisServiceProvider::class,
```

#### Update config/cache.php
```php
...
'stores' => [
        ...
        
        'multi-tenant-redis' => [
            'driver'     => 'multi-tenant-redis',
            'connection' => 'default',
        ],
    ],
...
```