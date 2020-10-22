<?php

namespace Elective\FormatterBundle\Parsers;

/**
 * Elective\FormatterBundle\Parsers\ParserInterface
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
interface ParserInterface
{
    /**
     * Returns default mimeType for this parser
     * @return string
     */
    public static function getDefaultMimeType(): string;

    /**
     * Parses data and returns the value
     *
     * @param   mixed       $data       Data to be parsed
     * @return  mixed       The value transformed
     * @throws  Bdcc_Exception when parse fails
     */
    public static function parse($data);

    /**
     * Parses data and returns the value
     *
     * @param   mixed       $data       Data to be parsed
     * @return  mixed       The value transformed
     * @throws  Bdcc_Exception when parse fails
     */
    public static function format($data);
}
