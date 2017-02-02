<?php

namespace UMFlint\Cache;

use Illuminate\Support\ServiceProvider;
use UMFlint\Cache\RedisStore\RedisStore;

class MultiTenantRedisServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $cache = $this->app['cache'];
        $redis = $this->app['redis'];
        $config = $this->app['config'];

        $cache->extend('multitenant.redis', function ($app) use ($cache, $redis, $config) {
            $connection = Arr::get($config['stores']['redis'], 'connection', 'default');

            return $cache->repository(new RedisStore($redis, $config['cache']['prefix'], $connection));
        });
    }
}
