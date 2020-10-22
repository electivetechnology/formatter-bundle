<?php

namespace Elective\FormatterBundle\Dispatcher;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Elective\FormatterBundle\Event\CacheTagClearableInterface;
use Elective\FormatterBundle\Listener\Cache\TagClearableEventListener;

/**
 * Elective\FormatterBundle\Dispatcher\CacheEventDispatcher
 *
 * @author Kris Rybak <kris.rybak@electivegroup.com>
 */
class CacheEventDispatcher extends EventDispatcher
{

    /**
     * @var TagClearableEventListener
     */
    private $listener;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher, TagClearableEventListener $listener)
    {
        $this->dispatcher = $dispatcher;
        $this->listener = $listener;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(object $event, string $eventName = null): object
    {
        if (null === $event) {
            $event = new Event();
        }

        if ($event instanceof CacheTagClearableInterface) {
            $this->dispatcher->addListener($event::NAME, [$this->listener, TagClearableEventListener::CACHE_TAG_CLEAR_EVENT]);
        }

        $this->dispatcher->dispatch($event, $eventName);

        return $event;
    }
}
