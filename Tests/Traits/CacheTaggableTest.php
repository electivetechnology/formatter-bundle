<?php

namespace Elective\FormatterBundle\Tests\Traits;

use Elective\FormatterBundle\Traits\CacheTaggable;
use PHPUnit\Framework\TestCase;

/**
 * Elective\FormatterBundle\Tests\Traits\CacheTaggableTest
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class CacheTaggableTest extends TestCase
{
    public function setTagsProvider()
    {
        return array(
            array(['foo', 'bar']),
            array(['foo', 1, rand(1, 100)]),
        );
    }

    /**
     * @dataProvider setTagsProvider
     */
    public function testSetGetTags($tags)
    {
        $cacheTaggableExample = new CacheTaggableExample();

        $this->assertInstanceOf(CacheTaggableExample::class, $cacheTaggableExample->setTags($tags));
        $this->assertSame($tags, $cacheTaggableExample->getTags());
    }

    public function addTagsProvider()
    {
        return array(
            array(['foo', 'bar']),
            array(['foo', 'bar', 1, 'loo']),
        );
    }

    /**
     * @dataProvider addTagsProvider
     */
    public function testAddTags($tags)
    {
        $cacheTaggableExample = new CacheTaggableExample();

        foreach ($tags as $tag) {
            $this->assertInstanceOf(CacheTaggableExample::class, $cacheTaggableExample->addTag($tag));
        }

        $this->assertEquals($tags, $cacheTaggableExample->getTags());
    }
}

class CacheTaggableExample
{
    use CacheTaggable;
}
