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

        return $this->logger;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
