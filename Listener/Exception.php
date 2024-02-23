<?php

namespace Elective\FormatterBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Elective\FormatterBundle\Response\FormatterInterface;
use Elective\FormatterBundle\Exception\ApiException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Elective\FormatterBundle\Listener\Exception
 *
 * @author Kris Rybak <kris.rybak@electivegroup.com>
 *
 */
class Exception implements EventSubscriberInterface
{
    /**
     * @var FormatterInterface
     */
    private $formatter;

    /**
     * @var string
     */
    private $env;

    public function __construct(FormatterInterface $formatter, $env = 'prod')
    {
        $this->formatter = $formatter;
        $this->env = $env;
    }

    /**
     * Get Formatter
     *
     * @return FormatterInterface
     */
    public function getFormatter(): FormatterInterface
    {
        return $this->formatter;
    }

    /**
     * Set Formatter
     *
     * @param $formatter FormatterInterface
     * @return self
     */
    public function setFormatter(FormatterInterface $formatter): self
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * Get Env
     *
     * @return string
     */
    public function getEnv(): string
    {
        return $this->env;
    }

    /**
     * Set Env
     *
     * @param $env string
     * @return self
     */
    public function setEnv(string $env): self
    {
        $this->env = $env;

        return $this;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        // Return symfony flavour exception in dev mode for all system exceptions
        if ($this->getEnv() != 'dev' || $exception instanceof ApiException) {
            $ret            = new \StdClass;
            $ret->message   = $exception->getMessage();
            $ret->code      = $exception->getCode();

            // HttpExceptionInterface is a special type of exception that
            // holds status code and header details
            if ($exception instanceof HttpExceptionInterface) {
                $statusCode = $exception->getStatusCode();
            } else {
                $statusCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
            }

            if ($exception instanceof ApiException) {
                $this->formatter->setHeaders($exception->getHeaders());
            }

            $response = $this->formatter->render($ret, $statusCode);
            $event->setResponse($response);
        }

        return $event;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException',
        );
    }
}
