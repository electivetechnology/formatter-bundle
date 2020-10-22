<?php

namespace Elective\FormatterBundle\Model;

use Symfony\Component\HttpFoundation\Response;

/**
 * Elective\FormatterBundle\Model\DuplicateEntryException
 * This exception is thrown when duplicate entry on constrain
 * key occurs.
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class DuplicateEntryException extends ModelException
{
    const MODEL_ERROR = Response::HTTP_CONFLICT;
}
