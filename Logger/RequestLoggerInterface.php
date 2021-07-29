<?php

namespace Elective\FormatterBundle\Logger;

/**
 * Elective\FormatterBundle\Logger\RequestLoggerInterface
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */
interface RequestLoggerInterface
{
    public function log($level, $message, array $context = array());
}
