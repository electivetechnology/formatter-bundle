<?php

namespace Elective\FormatterBundle\Cache\Triats;

use Elective\FormatterBundle\Response\FormatterInterface;
use Elective\FormatterBundle\Request\HandlerInterface;

/**
 * Elective\FormatterBundle\Cache\Triats\Outputable
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */

trait Outputable
{
    /**
     * @var FormatterInterface
     */
    private $formatter;

    /**
     * @var HandlerInterface
     */
    private $handler;

    /**
     * SetFormatter
     *
     * @parm $formatter FormatterInterface
     * @return self
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * GetFormatter
     *
     * @return FormatterInterface
     */
    public function getFormatter(): FormatterInterface
    {
        return $this->formatter;
    }

    /**
     * SetHandler
     *
     * @param $handler HandlerInterface
     * @return self
     */
    public function setHandler(HandlerInterface $handler)
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * GetHandler
     *
     * @return HandlerInterface
     */
    public function getHandler(): HandlerInterface
    {
        return $this->handler;
    }

    /**
     * Gets Post data
     *
     * @return  \StdClass
     */
    protected function getPostData(): \StdClass
    {
        return $this->handler->getPostData();
    }
}
