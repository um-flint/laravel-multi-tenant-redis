<?php

namespace UMFlint\Cache;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class MultiTenantRedisServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        Cache::extend('multi-tenant-redis', function ($app) {
            $redis = $app['redis'];
            $config = $app['config'];

            $connection = Arr::get($config['stores']['redis'], 'connection', 'default');

            return Cache::repository(new RedisStore($redis, $config['cache']['prefix'], $connection));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
