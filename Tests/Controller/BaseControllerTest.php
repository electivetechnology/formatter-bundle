<?php

namespace Elective\FormatterBundle\Tests\Controller;

use Elective\FormatterBundle\Controller\BaseController;
use Elective\FormatterBundle\Exception\ApiException;
use PHPUnit\Framework\TestCase;

/**
 * Elective\FormatterBundle\Tests\Controller\BaseControllerTest
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class BaseControllerTest extends TestCase
{
    public function exceptionDataProvider()
    {
        return array(
            array('My message'),
            array(),
            array('My message', 404, 70),
            array('My message', 404, 40400),
            array('My message', null, 40400),
        );
    }

    /**
     * @dataProvider exceptionDataProvider
     */
    public function testException($message = "", $statusCode = null, $code = 0)
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode($code);

        $stub = $this->getMockForAbstractClass(BaseController::class);
        $stub->exception($message, $statusCode, $code);
    }
}
