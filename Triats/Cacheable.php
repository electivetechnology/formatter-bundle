<?php

namespace Elective\FormatterBundle\Triats;

use Elective\FormatterBundle\Model\ModelInterface;
use Elective\FormatterBundle\Entity\IdableInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Elective\FormatterBundle\Triats\Cacheable
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */
trait Cacheable
{
    /**
     * @var CacheInterface
     */
    private $cacheAdapter;

    /**
     * Get CacheAdapter
     *
     * @return CacheInterface
     */
    public function getCacheAdapter(): CacheInterface
    {
        return $this->cacheAdapter;
    }

    /**
     * Get CacheAdapter
     *
     * @return self
     */
    public function setCacheAdapter(CacheInterface $cacheAdapter)
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

        return $this;
    }

    /**
     * Gets Cache key for given Model
     *
     * @param $model    ModelInterface
     * @param $item     IdableInterface
     * @param $user     UserInterface
     * @param $request  Request
     * @return string
     */
    public static function getModelCacheKey(ModelInterface $model, IdableInterface $item = null, UserInterface $user = null, Request $request = null): string
    {
        $key = $model->getName();

        // Add item if exist
        if ($item) {
            $key = $key . $item->getId();
        }

        // Add username if exists
        if ($user) {
            $key = $key . $user->getUsername();
        }

        // Add request details if exists
        if ($request) {
            $key = $key . serialize($request->query->all());
        }

        return md5($key);
    }
}
