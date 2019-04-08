<?php

namespace Elective\FormatterBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Elective\FormatterBundle\Controller\PreFlightTrait
 */
trait PreFlightTrait
{
    /**
     * @Route("", methods={"OPTIONS"})
     * @Route("/{spec}", methods={"OPTIONS"})
     */
    public function preFlight(): Response
    {
        return $this->output(null, Response::HTTP_NO_CONTENT);
    }
}
