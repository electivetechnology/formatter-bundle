<?php

namespace Elective\FormatterBundle\Model;

use Elective\FormatterBundle\Model\ModelInterface;
use Elective\FormatterBundle\Entity\IdableInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
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
     * @var ObjectManager
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

    public function __construct(ObjectManager $manager, EventDispatcherInterface $dispatcher, RequestStack $requestStack)
    {
        $this->manager      = $manager;
        $this->dispatcher   = $dispatcher;
        $this->requestStack = $requestStack;
    }

    /**
     * Get manager
     *
     * @return ObjectManager
     */
    public function getManager(): ObjectManager
    {
        return $this->manager;
    }

    /**
     * Set manager
     *
     * @param $manager ObjectManager
     * @return self
     */
    public function setManager(ObjectManager $manager): self
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
            if ($item instanceof IdableInterface || method_exists($item, 'getId')) {
                $tag = $tag . $item->getId();
            } elseif (is_string($item) || is_numeric($item)){
                $tag = $tag . $item;
            }
        }

        return $tag;
    }
}
