<?php

namespace Elective\FormatterBundle\Parsers;

use Elective\FormatterBundle\Parsers\ParserException;

/**
 * Elective\FormatterBundle\Parsers\Json
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class Json implements ParserInterface
{
    public const DEFAULT_MIME_TYPE = 'application/json';

    /**
     * Returns default mimeType for this parser
     * @return string
     */
    public static function getDefaultMimeType(): string
    {
        return self::DEFAULT_MIME_TYPE;
    }

    /**
     * Decodes JSON to StdClass
     * @param   json      $data
     * @return  StdClass
     */
    public static function parse($data)
    {
        return self::decode($data);
    }

    /**
     * Encodes StdClass to JSON
     * @param   mixed      $data
     * @return  StdClass
     */
    public static function format($data)
    {
        return self::encode($data);
    }

    /**
     * Decodes a JSON string into an object
     * @see http://uk3.php.net/manual/en/function.json-decode.php
     *
     * @param string $json
     * @param boolean $assoc When TRUE, returned objects will be converted
     * into associative arrays
     * @param int $depth User specified recursion depth
     * @param int $options Bitmask of JSON decode options. Currently only
     * JSON_BIGINT_AS_STRING is supported (default is to cast large integers
     * as floats)
     *
     * @throws Bdcc_Exception
     * @return mixed
     */
    public static function decode($json, $assoc = false, $depth = 512, $options = 0)
    {
        $ret = json_decode($json, $assoc, $depth, $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            self::throwJsonException();
        }

        return $ret;
    }

    /**
     * Encode a string, array, etc into a JSON string
     * @see http://uk3.php.net/manual/en/function.json-encode.php
     *
     * @param mixed $value The value being encoded. Can be any type except a
     * resource. This function only works with UTF-8 encoded data.
     * @param int $options Bitmask consisting of JSON_HEX_QUOT, JSON_HEX_TAG,
     * JSON_HEX_AMP, JSON_HEX_APOS, JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT,
     * JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT, JSON_UNESCAPED_UNICODE.
     *
     * @throws Bdcc_Exception
     * @return string
     */
    public static function encode($value, $options = 0)
    {
        $ret = json_encode($value, $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            self::throwJsonException();
        }

        return $ret;
    }

    /**
     * Throws a JSON exception based on the last JSON error that occurred
     *
     * @throws Bdcc_Exception
     */
    private static function throwJsonException()
    {
        // Check if the decoding was successful
        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                $msg = 'The maximum stack depth has been exceeded';
                break;

            case JSON_ERROR_STATE_MISMATCH:
                $msg = 'Invalid or malformed JSON';
                break;

            case JSON_ERROR_CTRL_CHAR:
                $msg = 'Control character error, possibly incorrectly encoded';
                break;

            case JSON_ERROR_SYNTAX:
                $msg = 'Syntax error';
                break;

            case JSON_ERROR_UTF8:
                $msg = 'Malformed UTF-8 characters, possibly incorrectly'
                    . ' encoded';
                break;

            default:
                $msg = 'An unknown error ocurred';
                break;
        }

        throw new ParserException(
            'Could not process JSON: ' . $msg,
            ParserException::BAD_REQUEST
        );
    }
}
