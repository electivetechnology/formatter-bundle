<?php

namespace Elective\FormatterBundle\Logger;

use Psr\Log\LoggerInterface;

/**
 * Elective\FormatterBundle\Logger\RequestLoggerInterface
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */
interface RequestLoggerInterface extends LoggerInterface
{
    public function log($level, $message, array $context = array());
}
