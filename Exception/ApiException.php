<?php

namespace Elective\FormatterBundle\Exception;

use Elective\FormatterBundle\Exception\ErrorCode;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Elective\FormatterBundle\Exception\ApiException
 *
 * @author @author Kris Rybak <kris.rybak@krisrybak.com>
 */
class ApiException extends \Exception implements HttpExceptionInterface
{
    protected $statusCode;

    protected $headers;

    public function __construct($message = null, $errorCode = null, $statusCode = 400, $headers = array())
    {
        parent::__construct($message, $errorCode);

        // // Assign default http status code

        // if (array_key_exists($errorCode, ErrorCode::$errorCodeToHttpStatusMap)) {
        //     $this->statusCode = ErrorCode::$errorCodeToHttpStatusMap[$errorCode];
        // }

        // Force http status code
        if ($statusCode !== null) {
            $this->statusCode = $statusCode;
        }

        $this->headers = $headers;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
