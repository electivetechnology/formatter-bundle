<?php

namespace Elective\FormatterBundle\Controller;

use Elective\FormatterBundle\Exception\ApiException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Elective\FormatterBundle\Controller\BaseController
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */
abstract class BaseController extends AbstractController
{
    /**
     * A shortcut method to throw ApiException
     *
     * @param   $message        string      Message to sent to user
     * @param   $statusCode     integer     HTTP Status code to use
     * @param   $errorCode      integer     Custom error code for debugging
     * @throws  ApiException    Throws ApiException
     */
    public function exception($message = null, $statusCode = null, $errorCode = null, $headers = array())
    {
        throw new ApiException($message, $errorCode, $statusCode, $headers);
    }
}
