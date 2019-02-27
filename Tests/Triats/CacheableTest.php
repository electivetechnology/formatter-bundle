<?php

namespace Elective\FormatterBundle\Tests\Triats;

use Elective\FormatterBundle\Triats\Cacheable;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

class A {
    use Cacheable;
}

class Adapter implements CacheInterface{
    public function get(string $key, callable $callback, float $beta = null, array &$metadata = null){}
    public function delete(string $key): bool {}
    public function getItem(string $key) {}
}

class Item {
    public function isHit(): bool {}
    public function get() {}
    public function set() {}
    public function expiresAfter() {}
    public function tag() {}
}

/**
 * Elective\FormatterBundle\Tests\Cache\Triats\CacheabletTest
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class CacheableTest extends TestCase
{
    public function testSetCacheAdapter() {
        $a = new A;
        $b = new Adapter;
        $this->assertInstanceOf(A::class, $a->setCacheAdapter($b));
        $this->assertInstanceOf(CacheInterface::class, $a->getCacheAdapter());
        $this->assertSame($b, $a->getCacheAdapter());
    }

    public function testGetCacheItem() {
        $data = new \StdClass;
        $data->foo = "bar";

        $nonExistingItem = $this->createMock(Item::class);

        // Configure the stub.
        $nonExistingItem->method('isHit')
             ->willReturn(false);

        $stub = $this->createMock(Adapter::class);

        // Configure the stub.
        $stub->method('getItem')
             ->willReturn($nonExistingItem);

        $a = new A;
        $this->assertInstanceOf(A::class, $a->setCacheAdapter($stub));
        $this->assertFalse($a->getCacheItem("key"));

        $existingItem = $this->createMock(Item::class);

        // Configure the stub.
        $existingItem->method('isHit')
            ->willReturn(true);
        $existingItem->method('get')
            ->willReturn(serialize($data));

        // Configure the stub.
        $adapter = $this->createMock(Adapter::class);
        $adapter->method('getItem')
             ->willReturn($existingItem);

        $aa = new A;
        $this->assertInstanceOf(A::class, $aa->setCacheAdapter($adapter));
        $this->assertEquals($data, $aa->getCacheItem("key"));
    }

    public function testSetCacheItem() {
        // Configure the stub.
        $adapter = $this->createMock(Adapter::class);
        $adapter->method('getItem')
             ->willReturn(true);

        $a = new A;
        $this->assertInstanceOf(A::class, $a->setCacheAdapter($adapter));
    }
}
