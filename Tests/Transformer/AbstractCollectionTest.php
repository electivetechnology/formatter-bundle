<?php

namespace Elective\FormatterBundle\Tests\Transformer;

use Elective\FormatterBundle\Transformer\AbstractCollection;
use Elective\FormatterBundle\Transformer\TransformerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Elective\FormatterBundle\Tests\Transformer\AbstractCollectionTest
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class AbstractCollectionTest extends TestCase
{
    protected $collectionTransformer;

    protected function setUp(): void
    {
        $modelTransformer = $this->createMock(TransformerInterface::class);

        $this->collectionTransformer = new SimpleCollectionTransformer($modelTransformer);
    }

    public function testConstructor()
    {
        $modelTransformer = $this->createMock(TransformerInterface::class);
        $collectionTransformer = $this->getMockBuilder(AbstractCollection::class)
            ->setConstructorArgs([$modelTransformer])
            ->getMock();

        $this->assertInstanceOf(TransformerInterface::class, $collectionTransformer->getModelTransformer());
    }

    public function testModelTransformer()
    {
        $modelTransformer = $this->createMock(TransformerInterface::class);
        $collectionTransformer = new SimpleCollectionTransformer($modelTransformer);
        
        $newModelTransformer = $this->createMock(TransformerInterface::class);
        $this->assertInstanceOf(AbstractCollection::class, $collectionTransformer->setModelTransformer($newModelTransformer));
        $this->assertSame($newModelTransformer, $collectionTransformer->getModelTransformer());
    }

    public function transformCollectionProvider()
    {
        $data = array();
        $collectionOne = array(1,3,5,10);

        $data[] = array($collectionOne);

        return $data;
    }

    /**
     * @dataProvider transformCollectionProvider
     */
    public function testTransform($collection)
    {
        $modelTransformer = $this->createMock(TransformerInterface::class);
        $modelTransformer->method('transform')->willReturn($collection);
        $collectionTransformer = new SimpleCollectionTransformer($modelTransformer);

        $this->assertTrue(is_array($collectionTransformer->transform($collection)));
        $this->assertCount(count($collection), $collectionTransformer->transform($collection));
    }
}

class SimpleCollectionTransformer extends AbstractCollection{
}
