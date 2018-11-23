<?php

namespace Elective\FormatterBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Elective\FormatterBundle\Exception\ErrorCode
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */
class ErrorCode
{
    const USER_NOT_FOUND = 4040001;
    const USER_EMAIL_PRIMARY_MISSING = 4040021;
    const PASSWORD_RESET_LIMIT_EXCEEDED = 4000001;

    public static $errorTexts = array(
        4040001 => 'User not found. Supplied username or email address is invalid',
        4040021 => 'No primary email has been defined for the user',
        4000001 => 'API rate limit exceeded for this call',
    );

    public static function getCodeText($code)
    {
        if (array_key_exists($code, self::$errorTexts)) {
            return self::$errorTexts[$code];
        }

        return null;
    }
}
