<?php

namespace Elective\FormatterBundle\Tests\Cache\Triats;

use Elective\FormatterBundle\Cache\Triats\Cacheable;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

class A {
    use Cacheable;
}

class B implements CacheInterface{
    public function get(string $key, callable $callback, float $beta = null, array &$metadata = null){}
    public function delete(string $key): bool {}
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
        $b = new B;
        $this->assertInstanceOf(A::class, $a->setCacheAdapter($b));
        $this->assertInstanceOf(CacheInterface::class, $a->getCacheAdapter());
        $this->assertSame($b, $a->getCacheAdapter());
    }
}
