<?php

namespace Elective\FormatterBundle\Logger;

/**
 * Elective\FormatterBundle\Logger\FormatterInterface
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */
interface FormatterInterface
{
    public function print(string $id, string $level, string $message, array $context = array()): string;
}
