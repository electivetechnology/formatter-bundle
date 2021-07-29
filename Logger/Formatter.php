<?php

namespace Elective\FormatterBundle\Logger;

use Elective\FormatterBundle\Logger\FormatterInterface;

class Formatter implements FormatterInterface
{
    public function print(string $id, string $level, string $message, array $context = array()): string
    {
        // Get current time
        $mtime      = microtime(true);

        // Format time
        $utime      = sprintf('%.4f', $mtime);
        $raw_time   = \DateTime::createFromFormat('U.u', $utime);
        $now        = $raw_time->format('Y-m-d H:i:s.u');

        // Generate message
        return sprintf("[%s] [%s] [%s]: %s\n", $id, $level, $now, $this->interpolate($message, $context));
    }

    /**
     * Interpolates context values into the message placeholders.
     */
    function interpolate($message, array $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            // check that the value can be cast to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}
