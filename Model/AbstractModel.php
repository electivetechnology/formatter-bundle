<?php

namespace Elective\FormatterBundle\Model;

use Elective\FormatterBundle\Model\ModelInterface;
use Elective\FormatterBundle\Entity\IdableInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Elective\FormatterBundle\Model\AbstractModel
 *
 * @author Kris Rybak <kris.rybak@electivegroup.com>
 */
abstract class AbstractModel implements ModelInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(EntityManagerInterface $manager, EventDispatcherInterface $dispatcher, RequestStack $requestStack)
    {
        $this->manager      = $manager;
        $this->dispatcher   = $dispatcher;
        $this->requestStack = $requestStack;
    }

    /**
     * Get manager
     *
     * @return EntityManagerInterface
     */
    public function getManager(): EntityManagerInterface
    {
        return $this->manager;
    }

    /**
     * Set manager
     *
     * @param $manager EntityManagerInterface
     * @return self
     */
    public function setManager(EntityManagerInterface $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get dispatcher
     *
     * @return EventDispatcherInterface
     */
    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    /**
     * Set dispatcher
     *
     * @param $dispatcher EventDispatcherInterface
     * @return self
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher): self
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

    /**
     * Get requestStack
     *
     * @return RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->requestStack;
    }

    /**
     * Set requestStack
     *
     * @param $requestStack RequestStack
     * @return self
     */
    public function setRequestStack(RequestStack $requestStack): self
    {
        $this->requestStack = $requestStack;

        return $this;
    }

    /**
     * Get Tag
     *
     * @param $item mixed
     * @return self
     */
    public function getTag($item = null): string
    {
        $tag = $this->getName();

        // Add item if exist
        if ($item) {
            if ($item instanceof IdableInterface) {
                $tag = $tag . $item->getId();
            } elseif (is_string($item) || is_numeric($item)){
                $tag = $tag . $item;
            }
        }

        return $tag;
    }
}
