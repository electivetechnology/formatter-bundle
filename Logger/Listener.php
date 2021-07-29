<?php

namespace Elective\FormatterBundle\Logger;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Elective\FormatterBundle\Logger\RequestLogger;

class Listener implements EventSubscriberInterface
{
    /**
     * @var RequestLoggerInterface
     */
    private $logger;

    public function __construct(RequestLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onTerminate(FinishRequestEvent $event)
    {
        $this->logger->debug('Request Completed after ' . round($this->logger->getRequestDuration()) . 'ms');
    }

    public function onRequest(RequestEvent $event)
    {
        $this->logger->debug('Request Started');
    }

    public function onResponseReady(ResponseEvent $event)
    {
        $this->logger->debug(
            'Response for the request in now ready. It took ' .
            round($this->logger->getRequestDuration()) .
            'ms to generate'
        );
    }

    public static function getSubscribedEvents(): array
    {
        return array(
            KernelEvents::REQUEST           => 'onRequest',
            KernelEvents::RESPONSE          => 'onResponseReady',
            KernelEvents::FINISH_REQUEST    => 'onTerminate',
        );
    }
}
