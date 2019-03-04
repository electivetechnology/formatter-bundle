<?php

namespace Elective\FormatterBundle\Tests\Listener\Cache;

use Elective\FormatterBundle\Listener\Cache\TagClearableEventListener;
use Elective\FormatterBundle\Event\CacheTagClearableInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

/**
 * Elective\FormatterBundle\Tests\Listener\Cache\TagClearableEventListenerTest
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class TagClearableEventListenerTest extends TestCase
{
    protected $listener;

    protected function setUp(): void
    {
        $cache = $this->createMock(TagAwareAdapter::class);

        $this->listener = new TagClearableEventListener($cache);
    }

    public function testSetGetCache()
    {
        $cache = $this->createMock(TagAwareAdapter::class);
        $this->assertInstanceOf(TagClearableEventListener::class, $this->listener->setCache($cache));
        $this->assertSame($cache, $this->listener->getCache());
    }

    public function testGetSubscribedEvents()
    {
        $this->assertTrue(is_array($this->listener->getSubscribedEvents()));
        $this->assertTrue(is_array($this->listener::getSubscribedEvents()));
    }

    public function testOnCacheTagEvent()
    {
        $event = $this->createMock(CacheTagClearableInterface::class);
        $event->method('getTags')->willReturn([]);
        $this->assertInstanceOf(TagClearableEventListener::class, $this->listener->onCacheTagEvent($event));
    }
}
