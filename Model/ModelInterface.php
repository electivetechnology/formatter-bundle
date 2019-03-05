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
    public static function getName(): string;

    /**
     * Gets tag of the model item
     *
     * @return string
     */
    public function getTag($item = null): string;
}
