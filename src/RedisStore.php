<?php

namespace UMFlint\Cache;

use Illuminate\Cache\RedisStore as RedisStoreBase;

class RedisStore extends RedisStoreBase
{
    /**
     * Name of the set that holds all the keys.
     *
     * @var string
     */
    protected $setName = 'key:store';

    /**
     * Add a cache key to the set.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $key
     */
    protected function addKey($key)
    {
        $this->connection()->command('SADD', [
            $this->prefix . $this->setName,
            $this->prefix . $key,
        ]);
    }

    /**
     * Remove a cache key from the set.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $key
     */
    protected function removeKey($key)
    {
        $this->connection()->command('SREM', [
            $this->prefix . $this->setName,
            $this->prefix . $key,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function put($key, $value, $minutes)
    {
        $this->addKey($key);

        parent::put($key, $value, $minutes);
    }

    /**
     * @inheritdoc
     */
    public function increment($key, $value = 1)
    {
        $this->addKey($key);

        return parent::increment($key, $value);
    }

    /**
     * @inheritdoc
     */
    public function decrement($key, $value = 1)
    {
        $this->addKey($key);

        return parent::decrement($key, $value);
    }

    /**
     * @inheritdoc
     */
    public function forever($key, $value)
    {
        $this->addKey($key);

        parent::forever($key, $value);
    }

    /**
     * @inheritdoc
     */
    public function forget($key)
    {
        $this->removeKey($key);

        return parent::forget($key);
    }

    /**
     * @inheritdoc
     */
    public function flush()
    {
        // Get the keys from the set.
        $keys = $this->connection()->command('SMEMBERS', [
            $this->prefix . $this->setName,
        ]);

        // Delete all the keys.
        $this->connection()->command('DEL', $keys);

        // Clear the set.
        $this->connection()->command('DEL', $this->prefix . $this->setName);
    }
}