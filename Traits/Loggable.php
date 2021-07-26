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
        $utime = sprintf('%.4f', microtime(TRUE));
        $raw_time = \DateTime::createFromFormat('U.u', $utime);
        $now = $raw_time->format('Y-m-d H:i:s.u');
        $uuid = (isset($context['uuid'])) ? $context['uuid'] : (string) rand(100,999);
        $message = sprintf(">>[%s] [%s]: %s", $uuid, $now, $message);

        $this->getLogger()->debug($message, $context);
    }
}
