<?php

namespace Elective\FormatterBundle\Parsers;

use Symfony\Component\HttpFoundation\Response;

/**
 * This method provides the default Exception for ParserInterface
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class ParserException extends \Exception
{
    const BAD_REQUEST = Response::HTTP_BAD_REQUEST;
}
