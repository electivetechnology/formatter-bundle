<?php

namespace Elective\FormatterBundle\Tests\Triats;

use Elective\FormatterBundle\Triats\Cacheable;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;
use Elective\FormatterBundle\Model\ModelInterface;
use Elective\FormatterBundle\Entity\IdableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

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
    public function testSetCacheAdapter()
    {
        $a = new A;
        $b = new Adapter;
        $this->assertInstanceOf(A::class, $a->setCacheAdapter($b));
        $this->assertInstanceOf(CacheInterface::class, $a->getCacheAdapter());
        $this->assertSame($b, $a->getCacheAdapter());
    }

    public function testGetCacheItem()
    {
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

    public function testSetCacheItem()
    {
        // Configure the stub.
        $adapter = $this->createMock(Adapter::class);
        $adapter->method('getItem')
             ->willReturn(true);

        $a = new A;
        $this->assertInstanceOf(A::class, $a->setCacheAdapter($adapter));
    }

    public function modelCacheKeyProvider()
    {
        return array(
            array('object', '123', null, null, 'cf9b9fa54b89a683743bcd1eb81b4683'),
            array('object', '123', 'jane.doe', null, '30b1e28233b6d10ebaa3cb54886def34'),
            array('object', null, 'jane.doe', null, '809d826bc56fc4cdfb126a6ffc2837e9'),
            array('object', 'abc', 'jane.doe', array(
                "filters" => array("a-b-c", "d-e-f")
            ), 'd216dd2a0f4603d9682dd357ea9f1c41'),
            array('object', 'abc', null, array(
                "filters" => array("a-b-c", "d-e-f")
            ), '3e1f31222c951ccf35328b391ec066cb'),
        );
    }

    /**
     * @dataProvider modelCacheKeyProvider
     */
    public function testGetModelCacheKey($modelName, $itemId = null, $username = null, $queryArray = null, $expectedKey)
    {
        $item       = null;
        $user       = null;
        $request    = null;

        $model = $this->createMock(ModelInterface::class);
        $model->method('getName')->willReturn($modelName);

        if ($itemId) {
            $item = $this->createMock(IdableInterface::class);
            $item->method('getId')->willReturn($itemId);
        }

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

        $a = new A;
        $this->assertSame($expectedKey, $a->getModelCacheKey($model, $item, $user, $request));
    }
}
