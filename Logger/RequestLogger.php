<?php

namespace Elective\FormatterBundle\Logger;

use Elective\FormatterBundle\Logger\RequestLoggerInterface;
use Elective\FormatterBundle\Logger\FormatterInterface;
use Elective\FormatterBundle\Response\FormatterInterface as ResponseFormatter;
use Symfony\Component\HttpFoundation\RequestStack;
use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;
use Ucc\Crypt\Hash;

/**
 * Elective\FormatterBundle\Logger\RequestLogger
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */
class RequestLogger extends AbstractLogger implements RequestLoggerInterface, LoggerInterface
{
    public const HEADER_NAME = 'X-Log-Id';

    /**
     * @var FormatterInterface
     */
    private $formatter;

    /**
     * @var ResponseFormatter
     */
    private $responseFormatter;

    /**
     * @var string
     */
    private $level;

    /**
     * @var string
     */
    private $id;

    /**
     * @var flat
     */
    private $startTime;

    private static $levels = [
        LogLevel::DEBUG => 0,
        LogLevel::INFO => 1,
        LogLevel::NOTICE => 2,
        LogLevel::WARNING => 3,
        LogLevel::ERROR => 4,
        LogLevel::CRITICAL => 5,
        LogLevel::ALERT => 6,
        LogLevel::EMERGENCY => 7,
    ];

    public function __construct(
        FormatterInterface $formatter,
        ResponseFormatter $responseFormatter = null,
        $level = LogLevel::DEBUG
    ) {
        $this->formatter            = $formatter;
        $this->responseFormatter    = $responseFormatter;
        $this->level                = $level;
        $this->bootstrap();         // Bootstrap logger
    }

    private function bootstrap()
    {
        // Log start
        $this->startTime = microtime(true);

        // Generate ID for this Request
        $this->id = $this->generateRequestId();

        // Set ID to response header
        if ($this->getResponseFormatter()) {
            $this->getResponseFormatter()->addHeader(self::HEADER_NAME, $this->id);
        }
    }

    /**
     * Get FormatterInterface
     */
    public function getFormatter(): FormatterInterface
    {
        return $this->formatter;
    }

    /**
     * Set FormatterInterface
     */
    public function setFormatter(FormatterInterface $formatter): self
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * Get ResponseFormatter
     */
    public function getResponseFormatter(): ?ResponseFormatter
    {
        return $this->responseFormatter;
    }

    /**
     * Set ResponseFormatter
     */
    public function setResponseFormatter(ResponseFormatter $responseFormatter): self
    {
        $this->responseFormatter = $responseFormatter;

        return $this;
    }

    /**
     * Get Level
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * Set Level
     */
    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function generateRequestId(): string
    {
        return Hash::generateSalt(12, false);
    }

    public function getStartTime(): float
    {
        return $this->startTime;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        // Log above requested level only
        if (self::$levels[strtolower($level)] < self::$levels[strtolower($this->getLevel())]) {
            return;
        }

        $stdout = fopen('php://stdout', 'w');
        @fwrite($stdout, $this->getFormatter()->print($this->getId(), $level, $message, $context));
    }

    public function getRequestDuration()
    {
        // Current time
        $mtime = microtime(true);

        // Difference
        $diff = $mtime - $this->getStartTime();

        return $diff * 1000;
    }
}
