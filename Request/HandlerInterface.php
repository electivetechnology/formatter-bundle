<?php

namespace Elective\FormatterBundle\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * Elective\FormatterBundle\Request\HandlerInterface
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
interface HandlerInterface
{
    /**
     * Returns data submitted via POST Request
     */
    public function getPostData();
}
