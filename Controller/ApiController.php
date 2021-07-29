<?php

namespace Elective\FormatterBundle\Controller;

use Elective\SecurityBundle\Token\TokenDecoderInterface;
use Elective\FormatterBundle\Traits\{
    Cacheable,
    Outputable,
    Filterable,
    Sortable,
    Loggable,
    EventDispatchable
};
use Elective\FormatterBundle\Response\FormatterInterface;
use Elective\FormatterBundle\Request\HandlerInterface;
use Elective\FormatterBundle\Controller\BaseController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Psr\Log\LoggerInterface;

/**
 * Elective\FormatterBundle\Controller\ApiController
 *
 * This ApiController class provides method to quickly output data
 * in the desired format using response formatter.
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class ApiController extends BaseController
{
    use Cacheable;
    use Outputable;
    use Filterable;
    use Sortable;
    use Loggable;
    use EventDispatchable;

    public function __construct(
        FormatterInterface $formatter,
        HandlerInterface $handler,
        TagAwareCacheInterface $cache,
        TokenDecoderInterface $tokenDecoder,
        LoggerInterface $logger,
        EventDispatcherInterface $cacheDispatcher,
        $defaultLifetime = 0
    ) {
        $this->formatter    = $formatter;
        $this->handler      = $handler;
        $this->setCacheAdapter($cache);
        $this->tokenDecoder = $tokenDecoder;
        $this->setLogger($logger);
        $this->setDispatcher($cacheDispatcher);
        $this->setDefaultLifetime($defaultLifetime);
    }
}
