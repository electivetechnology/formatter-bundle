<?php

namespace Elective\FormatterBundle\Cache\Triats;

use Symfony\Contracts\Cache\TagAwareCacheInterface;

/**
 * Elective\FormatterBundle\Cache\Triats\Cacheable
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */

trait Cacheable
{
    /**
     * @var TagAwareAdapterInterface
     */
    private $cacheAdapter;

    /**
     * Get Cache
     *
     * @return TagAwareAdapterInterface
     */
    public function getCacheAdapter()
    {
        return $this->cacheAdapter;
    }

    /**
     * Get Cache
     *
     * @return TagAwareAdapterInterface
     */
    public function setCacheAdapter($cacheAdapter)
    {
        $this->cacheAdapter = $cacheAdapter;

        return $this;
    }

    /**
     * Check cache and retrieve data if exists
     *
     * @param $key      string  Key to search for
     * @param $prefix   string  Prefix to use for key name
     * @param $useHash  boolean Whether to use md5 for hashing the key, default true
     * @return mixed
     */
    public function getCacheItem($key, $prefix = null, $useHash = true)
    {
        if ($useHash) {
            $key = md5($key);
        }

        // Fetch from cache
        $item = $this->cacheAdapter->getItem($prefix . $key);

        // Check cache item exists
        if (!$item->isHit()) {
            return false;
        }

        return unserialize($item->get());
    }

    /**
     * Sets cache item
     *
     * @param $key          string  Key to search for
     * @param $value        mixed   Value to save
     * @param $expiration   integer Number of seconds that item is valid for
     * @param $tags         array   List of tags to assign to cache item
     * @param $prefix       string  Prefix to use for key name
     * @param $useHash      boolean Whether to use md5 for hashing the key, default true
     * @return mixed
     */
    public function setCacheItem($key, $value, $expiration = 60, $tags = [], $prefix = null, $useHash = true)
    {
        if ($useHash) {
            $key = md5($key);
        }

        // Fetch from cache
        $item = $this->cacheAdapter->getItem($prefix . $key);

        // Check cache item exists
        if (!$item->isHit()) {
            // Oops, no cache item, let's set it
            $item->set(serialize($value));
            // Add expiration
            $item->expiresAfter($expiration);
            // Add tags
            $item->tag($tags);
            // Save item
            $this->cacheAdapter->save($item);
        }
    }
}
