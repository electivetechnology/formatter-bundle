<?php

namespace Elective\FormatterBundle\Listener\Cache;

use Elective\FormatterBundle\Event\CacheTagClearableInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

/**
 * Elective\FormatterBundle\Listener\Cache\TagClearableEventListener
 *
 * @author Kris Rybak <kris.rybak@electivegroup.com>
 */
class TagClearableEventListener implements EventSubscriberInterface
{
    /**
     * @var TagAwareAdapter
     */
    private $cache;

    public function __construct(TagAwareAdapter $cache)
    {
         $this->cache = $cache;
    }

    /**
     * Get Cache
     *
     * @return TagAwareAdapter
     */
    public function getCache(): TagAwareAdapter
    {
        return $this->cache;
    }

    /**
     * Set Cache
     *
     * @param $cache TagAwareAdapter
     * @return self
     */
    public function setCache(TagAwareAdapter $cache): self
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
