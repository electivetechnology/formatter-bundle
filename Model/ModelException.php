<?php

namespace Elective\FormatterBundle\Model;

use Symfony\Component\HttpFoundation\Response;

/**
 * Elective\FormatterBundle\Model\ModelException
 * This method provides the default Exception for ModelInterface
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class ModelException extends \Exception
{
    protected $errorCode;

    const MODEL_ERROR = Response::HTTP_INTERNAL_SERVER_ERROR;

    public function __construct($message = '', $code = 0, $errorCode = 0)
    {
        parent::__construct($message, $code);

        $this->setErrorCode($errorCode);
    }

    public function setErrorCode($errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
