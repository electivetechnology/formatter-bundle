<?php

namespace Elective\FormatterBundle\Response;

use Elective\FormatterBundle\Response\FormatterInterface;
use Elective\FormatterBundle\Parsers\ParserInterface;
use Elective\FormatterBundle\Parsers\Json as JsonParser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

/**
 * Elective\FormatterBundle\Response\Formatter
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class Formatter implements FormatterInterface
{
    const CONTENT_TYPE_HEADER = 'Content-Type';

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $parsers;

    /**
     * @var ParserInterface
     */
    private $defaultParser;

    public function __construct(RequestStack $requestStack = null, $parsers = array(), TagAwareAdapter $cache = null)
    {
        $this->requestStack         = $requestStack;
        $this->headers              = array();
        $this->parsers              = $parsers;
        $this->defaultParser        = new JsonParser;
    }

    /**
     * Takes data and returns Response object injecting necessary data
     * and setting up all header required.
     *
     * @return  Response
     * @var     $data   mixed
     */
    public function render($data = null, $status = Response::HTTP_OK, $headers = array()): Response
    {
        $response   = new Response();
        $ret        = null;

        // Set desired status
        if (is_int($status)) {
            $response->setStatusCode($status);
        }

        // Add custom headers
        $headers = array_merge($headers, $this->headers);
        $response->headers->add($headers);

        // Parse data
        if (!is_null($this->requestStack)) {
            $requestedContentType = $this->requestStack->getCurrentRequest()->headers->get(self::CONTENT_TYPE_HEADER);

            // Match parser
            $parser = $this->getParser($requestedContentType);

            // If requested parser has been found format data and add headers
            if ($parser) {
                // Format data
                $ret = $parser::format($data);

                // Add Headers
                $response->headers->add([self::CONTENT_TYPE_HEADER => $requestedContentType]);
            } else {
                // Try default parser
                if ($this->getDefaultParser() instanceof ParserInterface) {
                    $ret = $this->getDefaultParser()::format($data);

                    // Add Default Header
                    $response->headers->add([self::CONTENT_TYPE_HEADER => $this->getDefaultParser()::getDefaultMimeType()]);
                } else {
                    // If we got that far without setting formatting ret it means that none of the
                    // configured parsers is able to handle request, therefore we should send bad request
                    // response back to the client
                    $response->setStatusCode(Response::HTTP_BAD_REQUEST);

                    return $response;
                }
            }
        }

        // Set Content
        $response->setContent($ret);

        return $response;
    }

    /**
     * Set Headers
     *
     * @param array   $headers      Array of headers to set
     * @return FormatterInterface
     */
    public function setHeaders(array $headers)
    {
        foreach ($headers as $key => $header) {
            if (is_array($header)) {
                foreach ($header as $key => $value) {
                    $this->addHeader($key, $value);
                }
            } else {
                $this->addHeader($key, $header);
            }
        }

        return $this;
    }

    /**
     * Add Header
     *
     * @param string    $key    Header Name
     * @param string    $value  Header Value
     * @return FormatterInterface
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Get Headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Sets parsers
     *
     * THIS WILL OVERWRITE ANY DEFAULT OR EXISTING PARSERS WITH FOR A GIVEN CONTENT TYPE
     *
     * @param array  $parsers Array of parsers to use for given response type
     * @return FormatterInterface
     */
    public function setParsers(array $parsers)
    {
        foreach ($parsers as $contentType => $parser) {
            $this->addParser($contentType, $parser);
        }

        return $this;
    }

    /**
     * Gets parsers
     *
     * @return array
     */
    public function getParsers()
    {
        return $this->parsers;
    }

    /**
     * Gets parser
     *
     * @return ParserInterface|null
     */
    public function getParser($contentType)
    {
        if (isset($this->parsers[$contentType])) {
            return $this->parsers[$contentType];
        }

        return null;
    }

    /**
     * Add parser
     *
     * @param string  $contentType    Content type to use the parser for
     * @param array   $parser         Parser to use
     * @return FormatterInterface
     */
    public function addParser($contentType, ParserInterface $parser)
    {
        $this->parsers[$contentType] = $parser;

        return $this;
    }

    /**
     * Removes parser
     *
     * @param string  $contentType    Content type to use the parser for
     * @return FormatterInterface
     */
    public function removeParser($contentType)
    {
        if (array_key_exists($contentType, $this->parsers)) {
            unset($this->parsers[$contentType]);
        }

        return $this;
    }

    /**
     * Set Default Parser
     *
     * @param ParserInterface   $parser         Parser to use
     * @return FormatterInterface
     */
    public function setDefaultParser(ParserInterface $parser)
    {
        $this->defaultParser = $parser;

        return $this;
    }

    /**
     * Get Default Parser
     *
     * @return ParserInterface|null
     */
    public function getDefaultParser()
    {
        return $this->defaultParser;
    }
}
