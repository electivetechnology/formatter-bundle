<?php

namespace Elective\FormatterBundle\Traits;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Elective\FormatterBundle\Traits\EventDispatchable
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */
trait EventDispatchable
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * Get dispatcher
     *
     * @return EventDispatcherInterface
     */
    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    /**
     * Set dispatcher
     *
     * @param $dispatcher EventDispatcherInterface
     * @return self
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher): self
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }
}
