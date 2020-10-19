<?php

namespace Elective\FormatterBundle\Tests\Traits;

use Elective\FormatterBundle\Traits\Filterable;
use Elective\FormatterBundle\Request\Handler;
use Elective\FormatterBundle\Exception\ApiException;
use Ucc\Data\Types\Pseudo\FilterType;
use PHPUnit\Framework\TestCase;

/**
 * Elective\FormatterBundle\Tests\Traits\FilterableTest
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class FilterableTest extends TestCase
{
    public function emptyFiltersProvider()
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
     * @dataProvider emptyFiltersProvider
     */
    public function testGetFiltersWithoutHandler($validFilters, $expected)
    {
        $filterable = new FilterableExample;

        $this->assertTrue(is_array($filterable->getFilters($validFilters)));
        $this->assertEquals($expected, $filterable->getFilters($validFilters));
    }

    public function filtersProvider()
    {
        $data = array();
        $validFilters   = ['fields' => ['label.id', 'label.name']];

        $requested      = ['and-label.name-inci-value-foo'];
        $filter         = FilterType::check($requested, $validFilters);
        $data[] = [$requested, $validFilters, [$filter]];

        $requested      = ['and-label.name-inci-value-foo', 'and-label.name-eq-value-bar'];
        $filter         = FilterType::check($requested, $validFilters);
        $data[] = [$requested, $validFilters, [$filter]];

        $requested      = ['and-label.name-inci-value-foo'];
        $requested2     = ['and-label.name-eq-value-bar'];
        $filter         = FilterType::check($requested, $validFilters);
        $filter2        = FilterType::check($requested2, $validFilters);
        $data[] = [[$requested, $requested2], $validFilters, [$filter, $filter2]];
        
        return $data;
    }

    /**
     * @dataProvider filtersProvider
     */
    public function testGetFiltersWithHandler($requested, $validFilters, $expected)
    {
        $filterable = new FilterableExample();
        $filterable->handler = $this->createMock(Handler::class);
        $filterable->handler->method('getFilters')->willReturn($requested);

        $this->assertTrue(is_array($filterable->getFilters($validFilters)));
        $this->assertEquals($expected, $filterable->getFilters($validFilters));
    }

    public function invalidFiltersProvider()
    {
        $data = array();

        // Invalid filter fields
        $validFilters   = ['fields' => ['label.id', 'label.name']];
        $requested      = ['and-label.number-inci-value-foo'];
        $data[]         = [$requested, $validFilters];

        // Invalid filter operand
        $validFilters   = ['fields' => ['label.id', 'label.name']];
        $requested      = ['and-label.name-invalid-value-foo'];
        $data[]         = [$requested, $validFilters];

        return $data;
    }

    /**
     * @dataProvider invalidFiltersProvider
     */
    public function testGetFiltersWithHandlerFail($requested, $validFilters)
    {
        $this->expectException(ApiException::class);

        $filterable = new FilterableExample();
        $filterable->handler = $this->createMock(Handler::class);
        $filterable->handler->method('getFilters')->willReturn($requested);

        $this->assertTrue(is_array($filterable->getFilters($validFilters)));
    }
}

class FilterableExample {
    use Filterable;
}
