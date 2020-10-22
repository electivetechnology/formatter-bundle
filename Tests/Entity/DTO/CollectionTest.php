<?php

namespace Elective\FormatterBundle\Tests\Entity\DTO;

use Elective\FormatterBundle\Entity\DTO\Collection;
use PHPUnit\Framework\TestCase;

/**
 * Elective\FormatterBundle\Tests\Entity\DTO\CollectionTest
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class CollectionTest extends TestCase
{
    public function resultsProvider()
    {
        return array(
            array([1,2,3,5,7,11]),
            array(['1','2','3','5','8','13']),
        );
    }

    /**
     * @dataProvider resultsProvider
     */
    public function testResultsGetandSet($results)
    {
        $dto = new Collection();
        $this->assertInstanceOf(Collection::class, $dto->setResults($results));
        $this->assertSame($results, $dto->getResults());
    }

    public function totalCountProvider()
    {
        return array(
            array(),
            array(71),
            array(rand(100, 1000)),
        );
    }

    /**
     * @dataProvider totalCountProvider
     */
    public function testTotalCountGetandSet($totalcount = 0)
    {
        if (!empty($totalcount)) {
            $dto = new Collection(null, $totalcount);
        } else {
            $dto = new Collection();
        }
        
        $this->assertInstanceOf(Collection::class, $dto->setTotalCount($totalcount));
        $this->assertSame($totalcount, $dto->getTotalCount());
    }
}
