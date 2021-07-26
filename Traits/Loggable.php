<?php

namespace Elective\FormatterBundle\Traits;

use Psr\Log\LoggerInterface;

/**
 * Elective\FormatterBundle\Traits\Loggable
 *
 * @author Kris Rybak <kris.rybak@elective.io>
 */
trait Loggable
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $context;

    /**
     * @var flat
     */
    private $startTime;

    public function setContext($context, $startTime = 0): self
    {
        $this->context = $context;

        // Reset startTime time
        if (!$startTime) {
            $startTime = microtime(TRUE);
        }
        $this->setStartTime($startTime);

        return $this;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function setStartTime($startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getStartTime(): float
    {
        return $this->startTime;
    }

    public function setLogger($logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function debug($message, array $context = array()) {
        $mtime = microtime(TRUE);
        $utime = sprintf('%.4f', $mtime);
        $raw_time = \DateTime::createFromFormat('U.u', $utime);
        $now = $raw_time->format('Y-m-d H:i:s.u');

        // Override context
        if ($context) {
            $this->setContext($context, $mtime);
        }

        $uuid = (isset($this->getContext()['uuid'])) ? $this->getContext()['uuid'] : (string) rand(100,999);
        $message = sprintf(">>[%s] [%s]: %s", $uuid, $now, $message);

        $this->getLogger()->debug($message, $this->getContext());
    }

    public function debugContext() {
        // Current time
        $mtime = microtime(TRUE);

        // Difference
        $diff = $mtime - $this->getStartTime();

        $message = sprintf("Request took %d ms to process", $diff *1000);
        $this->debug($message);
    }
}
