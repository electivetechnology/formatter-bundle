<?php

namespace Elective\FormatterBundle\Listener\Cache;

use Elective\FormatterBundle\Event\CacheTagClearableInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

/**
 * Elective\FormatterBundle\Listener\Cache\TagClearableEventListener
 *
 * @author Kris Rybak <kris.rybak@electivegroup.com>
 */
class TagClearableEventListener implements EventSubscriberInterface
{
    public const CACHE_TAG_CLEAR_EVENT = 'onCacheTagEvent';

    /**
     * @var TagAwareCacheInterface
     */
    private $cache;

    public function __construct(TagAwareCacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Get Cache
     *
     * @return TagAwareCacheInterface
     */
    public function getCache(): TagAwareCacheInterface
    {
        return $this->cache;
    }

    /**
     * Set Cache
     *
     * @param $cache TagAwareCacheInterface
     * @return self
     */
    public function setCache(TagAwareCacheInterface $cache): self
    {
        $this->cache = $cache;

        return $this;
    }

    public static function getSubscribedEvents(): array
    {
        return array();
    }

    /**
     * Processes cache clearable event
     *
     * @param $event Event
     * @return self
     */
    public function onCacheTagEvent(CacheTagClearableInterface $event)
    {
        $this->cache->invalidateTags($event->getTags());

        return $this;
    }
}
