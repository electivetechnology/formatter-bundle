<?php

namespace Elective\FormatterBundle\Tests\Traits;

use Elective\FormatterBundle\Traits\Sortable;
use Elective\FormatterBundle\Request\Handler;
use Elective\FormatterBundle\Exception\ApiException;
use Ucc\Data\Types\Pseudo\SortType;
use PHPUnit\Framework\TestCase;

/**
 * Elective\FormatterBundle\Tests\Traits\SortableTest
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class SortableTest extends TestCase
{
    public function emptySortsProvider()
    {
        return array(
            array(
                [],[]
            ),
            array(
                ['fields' => ['label.id', 'label.name']],[]
            ),
        );
    }

    /**
     * @dataProvider emptySortsProvider
     */
    public function testTestGetSortsWithoutHandler($validSorts, $expected)
    {
        $sortable = new SimpleSortableExample();

        $this->assertTrue(is_array($sortable->getSorts($validSorts)));
        $this->assertEquals($expected, $sortable->getSorts($validSorts));
    }

    public function sortsProvider()
    {
        $data           = array();
        $validSorts   = ['fields' => ['label.id', 'label.name']];

        $requested  = ['label.id-asc'];
        $sorts      = SortType::check($requested, $validSorts);
        $data[]     = [$requested, $validSorts, $sorts];

        $requested  = ['label.id-asc', 'label.name-desc'];
        $sorts      = SortType::check($requested, $validSorts);
        $data[]     = [$requested, $validSorts, $sorts];

        return $data;
    }

    /**
     * @dataProvider sortsProvider
     */
    public function testGetSortsWithHandler($requested, $validSorts, $expected)
    {
        $sortable = new SimpleSortableExample();
        $sortable->handler = $this->createMock(Handler::class);
        $sortable->handler->method('getSorts')->willReturn($requested);

        $this->assertTrue(is_array($sortable->getSorts($validSorts)));
        $this->assertEquals($expected, $sortable->getSorts($validSorts));
    }

    public function invalidSortsProvider()
    {
        $data = array();

        // Invalid sort fields
        $validSorts = ['fields' => ['label.id', 'label.name']];
        $requested  = ['label.number-asc'];
        $data[]     = [$requested, $validSorts];

        // Invalid sort direction
        $validSorts = ['fields' => ['label.id', 'label.name']];
        $requested  = ['label.number-down'];
        $data[]     = [$requested, $validSorts];

        return $data;
    }

    /**
     * @dataProvider invalidSortsProvider
     */
    public function testGetSortsWithHandlerFail($requested, $validSorts)
    {
        $this->expectException(ApiException::class);
        $sortable = new SimpleSortableExample();
        $sortable->handler = $this->createMock(Handler::class);
        $sortable->handler->method('getSorts')->willReturn($requested);

        $this->assertTrue(is_array($sortable->getSorts($validSorts)));
    }
}

class SimpleSortableExample
{
    use Sortable;
}
