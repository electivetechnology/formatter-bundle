<?php

namespace Elective\FormatterBundle\Tests\Listener\Cache;

use Elective\FormatterBundle\Listener\Exception;
use Elective\FormatterBundle\Response\Formatter;
use Elective\FormatterBundle\Response\FormatterInterface;
use PHPUnit\Framework\TestCase;
use Elective\FormatterBundle\Exception\ApiException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Elective\FormatterBundle\Tests\Listener\ExceptionTest
 * @group listener
 * @author Kris Rybak <kris@electivegroup.com>
 */
class ExceptionTest extends TestCase
{
    protected $listener;

    protected function setUp(): void
    {
        $requestStack   = $this->createMock(RequestStack::class);
        $request        = $this->createMock(Request::class);
        $requestStack->method('getCurrentRequest')->willReturn($request);
        $formatter      = new Formatter($requestStack);
        $this->listener = new Exception($formatter);
    }

    public function testSetGetFormatter()
    {
        $formatter = $this->createMock(FormatterInterface::class);
        $this->assertInstanceOf(Exception::class, $this->listener->setFormatter($formatter));
        $this->assertSame($formatter, $this->listener->getFormatter());
    }

    public function envProvider()
    {
        return array(
            array('dev'),
            array('test'),
            array('prod'),
        );
    }

    /**
     * @dataProvider envProvider
     */
    public function testSetGetEnv($env)
    {
        $this->assertInstanceOf(Exception::class, $this->listener->setEnv($env));
        $this->assertSame($env, $this->listener->getEnv());
    }

    public function onKernelExceptionProvider()
    {
        $data = array();
        $kernel = $this->createMock(HttpKernelInterface::class);
        $request = $this->createMock(Request::class);

        $exception = new ApiException('foo', 400000, 400, ['X-foo' => 'bar']);
        $event = new GetResponseForExceptionEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $exception);

        $data[] = array('dev', $event, true, 400, 'X-foo', 'bar');
        $data[] = array('test', $event, true, 400, 'X-foo', 'bar');
        $data[] = array('prod', $event, true, 400, 'X-foo', 'bar');

        $exception = new \Exception('foo');
        $event = new GetResponseForExceptionEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $exception);
        $data[] = array('prod', $event, true, 500);

        return $data;
    }

    /**
     * @dataProvider onKernelExceptionProvider
     */
    public function testOnKernelException($env, $event, $isResponse = false, $expectedStatusCode = null, $headerKey = null, $headerVal = null)
    {
        $requestStack   = $this->createMock(RequestStack::class);
        $request        = $this->createMock(Request::class);
        $requestStack->method('getCurrentRequest')->willReturn($request);
        $formatter      = new Formatter($requestStack);
        $listener   = new Exception($formatter, $env);
        $responseEvent = $listener->onKernelException($event);
        $this->assertInstanceOf(GetResponseForExceptionEvent::class, $responseEvent);

        if ($expectedStatusCode) {
            $this->assertEquals($expectedStatusCode, $responseEvent->getResponse()->getStatusCode());
        }

        if ($isResponse) {
            $this->assertInstanceOf(Response::class, $responseEvent->getResponse());
        }

        $this->assertEquals($headerVal, $responseEvent->getResponse()->headers->get($headerKey));
    }
}
