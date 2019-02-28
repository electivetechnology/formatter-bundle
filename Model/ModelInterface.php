<?php

namespace Elective\FormatterBundle\Model;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Elective\FormatterBundle\Model\ModelInterface
 *
 * @author Kris Rybak <kris.rybak@electivegroup.com>
 */
interface ModelInterface
{
    /**
     * Gets name of the model. This should be unique.
     *
     * @return string
     */
    public function getName(): string;
}
