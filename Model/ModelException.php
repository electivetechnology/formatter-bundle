<?php

namespace Elective\FormatterBundle\Model;

use Symfony\Component\HttpFoundation\Response;

/**
 * This method provides the default Exception for ModelInterface
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class ParserException extends \Exception
{
    const MODEL_ERROR = Response::HTTP_INTERNAL_SERVER_ERROR;
}
