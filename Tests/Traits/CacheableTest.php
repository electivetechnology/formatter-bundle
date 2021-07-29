<?php

namespace Elective\FormatterBundle\Tests\Traits;

use Elective\FormatterBundle\Traits\Cacheable;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;
use Elective\FormatterBundle\Model\ModelInterface;
use Elective\FormatterBundle\Entity\IdableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class A
{
    use Cacheable;
}

class Adapter implements CacheInterface
{
    public function get(string $key, callable $callback, float $beta = null, array &$metadata = null)
    {
    }
    public function delete(string $key): bool
    {
    }
    public function getItem(string $key)
    {
    }
}

class Item
{
    public function isHit(): bool
    {
    }
    public function get()
    {
    }
    public function set()
    {
    }
    public function expiresAfter()
    {
    }
    public function tag()
    {
    }
}

/**
 * Elective\FormatterBundle\Tests\Cache\Traits\CacheabletTest
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class CacheableTest extends TestCase
{
    public function testSetCacheAdapter()
    {
        $a = new A();
        $b = new Adapter();
        $this->assertInstanceOf(A::class, $a->setCacheAdapter($b));
        $this->assertInstanceOf(CacheInterface::class, $a->getCacheAdapter());
        $this->assertSame($b, $a->getCacheAdapter());
    }

    public function testGetCacheItem()
    {
        $data = new \StdClass();
        $data->foo = "bar";

        $nonExistingItem = $this->createMock(Item::class);

        // Configure the stub.
        $nonExistingItem->method('isHit')
             ->willReturn(false);

        $stub = $this->createMock(Adapter::class);

        // Configure the stub.
        $stub->method('getItem')
             ->willReturn($nonExistingItem);

        $a = new A();
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

        $aa = new A();
        $this->assertInstanceOf(A::class, $aa->setCacheAdapter($adapter));
        $this->assertEquals($data, $aa->getCacheItem("key"));
    }

    public function testSetCacheItem()
    {
        // Configure the stub.
        $adapter = $this->createMock(Adapter::class);
        $adapter->method('getItem')
             ->willReturn(true);

        $a = new A();
        $this->assertInstanceOf(A::class, $a->setCacheAdapter($adapter));
    }

    public function modelCacheKeyProvider()
    {
        $item = $this->createMock(IdableInterface::class);
        $item->method('getId')->willReturn('123');

        return array(
            array($item, null, null, 'cf9b9fa54b89a683743bcd1eb81b4683'),
            array('123', null, null, 'cf9b9fa54b89a683743bcd1eb81b4683'),
            array(123, null, null, 'cf9b9fa54b89a683743bcd1eb81b4683'),
            array($item, 'jane.doe', null, '30b1e28233b6d10ebaa3cb54886def34'),
            array(null, 'jane.doe', null, '809d826bc56fc4cdfb126a6ffc2837e9'),
            array([], 'jane.doe', null, '809d826bc56fc4cdfb126a6ffc2837e9'),
            array(new \StdClass(), 'jane.doe', null, '809d826bc56fc4cdfb126a6ffc2837e9'),
            array([1,2,3], 'jane.doe', null, '3978667c6e4e3260aedd488dd33e4859'),
            array(json_decode('[1,2,3]'), 'jane.doe', null, '3978667c6e4e3260aedd488dd33e4859'),
            array(json_decode('{"foo":"bar"}'), 'jane.doe', null, '809d826bc56fc4cdfb126a6ffc2837e9'),
            array($item, 'jane.doe', array(
                "filters" => array("a-b-c", "d-e-f")
            ), '9dd16733c5b4090a3fc6a3e8da58ac64'),
            array($item, null, array(
                "filters" => array("a-b-c", "d-e-f")
            ), '6b8cbe3fe01a3a0c1456958f9c9a1a68'),
        );
    }

    /**
     * @dataProvider modelCacheKeyProvider
     */
    public function testGetModelCacheKey($item = null, $username = null, $queryArray = null, $expectedKey)
    {
        $user       = null;
        $request    = null;

        $model = new SimpleModel();
        //$model = $this->createMock(ModelInterface::class);
        //$model->method('getName')->willReturn($modelName);

        if ($username) {
            $user = $this->createMock(UserInterface::class);
            $user->method('getUsername')->willReturn($username);
        }

        if ($queryArray) {
            $request = $this->createMock(Request::class);
            $query = $this->createMock(ParameterBag::class);
            $query->method('all')->willReturn($queryArray);
            $request->query = $query;
        }

        $a = new A();
        $this->assertSame($expectedKey, $a->getModelCacheKey($model, $item, $user, $request));
    }

    public function defaultLifetimeProvider()
    {
        return array(
            array(5),
            array(50),
        );
    }

    /**
     * @dataProvider defaultLifetimeProvider
     */
    public function testDefaultLifetime($ttl)
    {
        $a = new A();
        $this->assertInstanceOf(A::class, $a->setDefaultLifetime($ttl));
        $this->assertSame($ttl, $a->getDefaultLifetime());
    }
}

class SimpleModel implements ModelInterface
{
    public static function getName(): string
    {
        return 'object';
    }

    public function getTag($item = null): string
    {
        return '';
    }
}
