<?php

namespace Elective\FormatterBundle\Response;

use Symfony\Component\HttpFoundation\Response;

/**
 * Elective\FormatterBundle\Response\FormatterInterface
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
interface FormatterInterface
{
    public function render($data = null, $status = Response::HTTP_OK, $headers = array()): Response;
    public function setHeaders(array $headers);
}
