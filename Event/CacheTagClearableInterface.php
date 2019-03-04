<?php

namespace Elective\FormatterBundle\Event;

/**
 * Elective\FormatterBundle\Event\CacheTagClearableInterface
 *
 * @author @author Kris Rybak <kris.rybak@electivegroup.com>
 */
interface CacheTagClearableInterface
{
    /**
     * Get Tags
     *
     * @return array
     */
    public function getTags(): array;
}
