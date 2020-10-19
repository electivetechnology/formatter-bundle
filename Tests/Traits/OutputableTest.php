<?php

namespace Elective\FormatterBundle\Tests\Traits;

use Elective\FormatterBundle\Traits\Outputable;
use Elective\FormatterBundle\Response\FormatterInterface;
use Elective\FormatterBundle\Request\HandlerInterface;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\TestCase;

/**
 * Elective\FormatterBundle\Tests\Traits\OutputableTest
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

    public function testSetFormatterFail()
    {
        $this->expectException(\TypeError::class);
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

    public function testSetHandlerFail()
    {
        $this->expectException(\TypeError::class);
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
