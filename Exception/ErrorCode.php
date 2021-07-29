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
    public const MALFORMED_FILTER              = 4000101;
    public const MALFORMED_SORT                = 4000102;
    public const AUTHENTICATION_FAILURE        = 4010001;
    public const AUTHENTICATION_REQUIRED       = 4010002;
    public const USER_NOT_FOUND                = 4040001;
    public const USER_EMAIL_PRIMARY_MISSING    = 4040021;
    public const USER_TOKEN_MALFORMED          = 4030001;
    public const USER_TOKEN_INVALID            = 4030002;
    public const USER_ORGANISATION_MISMATCH    = 4030003;
    public const PASSWORD_RESET_LIMIT_EXCEEDED = 4290001;
    public const PASSWORD_TOKEN_INVALID        = 4040031;
    public const DUPLICATE_ENTRY               = 4090001;

    public static $errorTexts = array(
        4000101 => 'Malformed filter options',
        4000102 => 'Malformed sort options',
        4010001 => 'Invalid Credentials',
        4010002 => 'Authentication Required',
        4030001 => 'Malformed user token. User token could not be parsed',
        4030002 => 'Invalid user token. User token is not valid',
        4030003 => 'User does not belong to given Organisation',
        4040001 => 'User not found. Supplied username or email address is invalid',
        4040021 => 'No primary email has been defined for the user',
        4040031 => 'Password token is not valid',
        4090001 => 'Duplicate entry',
        4290001 => 'API rate limit exceeded for this call',
    );

    public static function getCodeText($code)
    {
        if (array_key_exists($code, self::$errorTexts)) {
            return self::$errorTexts[$code];
        }

        return null;
    }
}
