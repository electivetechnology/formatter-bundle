<?php

namespace Elective\FormatterBundle\Tests\Traits;

use Elective\FormatterBundle\Traits\Loggable;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;

class LoggableClass {
    use Loggable;
}

class Logger implements LoggerInterface{
    public function emergency($message, array $context = array()){}
    public function alert($message, array $context = array()){}
    public function critical($message, array $context = array()){}
    public function error($message, array $context = array()){}
    public function warning($message, array $context = array()){}
    public function notice($message, array $context = array()){}
    public function info($message, array $context = array()){}
    public function debug($message, array $context = array()){}
    public function log($level, $message, array $context = array()){}
}

/**
 * Elective\FormatterBundle\Tests\Cache\Traits\LoggableTest
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class LoggableTest extends TestCase
{
    public function testSetGetLogger()
    {
        $a = new LoggableClass();
        $logger = new Logger();
        $this->assertInstanceOf(A::class, $a->setLogger($logger));
        $this->assertInstanceOf(LoggerInterface::class, $a->getLogger());
        $this->assertSame($logger, $a->getLogger());
    }
}
