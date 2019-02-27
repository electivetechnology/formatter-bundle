<?php

namespace Elective\FormatterBundle\Tests\Triats;

use Elective\FormatterBundle\Triats\Outputable;
use Elective\FormatterBundle\Response\FormatterInterface;
use Elective\FormatterBundle\Request\HandlerInterface;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\TestCase;

/**
 * Elective\FormatterBundle\Tests\Triats\OutputableTest
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class OutputableTest extends TestCase
{
    public function testSetFormatterPass()
    {
        $formatter = new OutputableFormatter();
        $testClass = new OutputableExample();

        $this->assertInstanceOf(OutputableExample::class, $testClass->setFormatter($formatter));
        $this->assertSame($formatter, $testClass->getFormatter());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSetFormatterFail()
    {
        $formatter = new \StdClass;
        $testClass = new OutputableExample();

        $this->assertInstanceOf(OutputableExample::class, $testClass->setFormatter($formatter));
    }

    public function testSetHandlerPass()
    {
        $handler = new OutputableHandler();
        $testClass = new OutputableExample();

        $this->assertInstanceOf(OutputableExample::class, $testClass->setHandler($handler));
        $this->assertSame($handler, $testClass->getHandler());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSetHandlerFail()
    {
        $handler = new \StdClass;
        $testClass = new OutputableExample();

        $this->assertInstanceOf(OutputableExample::class, $testClass->setHandler($handler));
    }
}

class OutputableExample {
    use Outputable;
}

class OutputableFormatter implements FormatterInterface{
    public function render($data = null, $status = Response::HTTP_OK, $headers = array()): Response {

    }
}

class OutputableHandler implements HandlerInterface{
    public function getPostData(): \StdClass {}
}
