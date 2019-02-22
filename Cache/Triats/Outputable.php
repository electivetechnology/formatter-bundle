<?php

namespace Elective\FormatterBundle\Cache\Triats;

use Elective\FormatterBundle\Response\FormatterInterface;
use Elective\FormatterBundle\Request\HandlerInterface;
use Symfony\Component\HttpFoundation\Response;

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
     * @param $formatter FormatterInterface
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

    /**
     * Sends data out to consumer
     *
     * @param $data     mixed
     * @param $status   int
     * @param $headers  array
     * @return Response
     */
    protected function output($data, $status = null, $headers = []): Response
    {
        return $this->formatter->render($data, $status, $headers);
    }
}
