<?php

namespace Elective\FormatterBundle\Request;

use Elective\FormatterBundle\Parsers\Json;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Elective\FormatterBundle\Request\Handler
 *
 * @author Kris Rybak <kris.rybak@krisrybak.com>
 */
class Handler implements HandlerInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $defaultFormat;

    public function __construct(RequestStack $requestStack = null)
    {
        $this->requestStack     = $requestStack;
        $this->defaultFormat    = self::FORMAT_BODY_RAW_JSON;
    }

    const   FORMAT_BODY_FORM_DATA       = 'multipart/form-data',
            FORMAT_BODY_URLENCODED      = 'application/x-www-form-urlencoded',
            FORMAT_BODY_RAW_JSON        = 'application/json',
            FORMAT_BODY_RAW_TEXT        = 'text/plain',
            FORMAT_BODY_RAW_CSV         = 'text/csv';

    /**
     * Array of supported request body formats
     *
     * @static array
     */
    public static $supportedFormats = array(
        self::FORMAT_BODY_FORM_DATA,
        self::FORMAT_BODY_URLENCODED,
        self::FORMAT_BODY_RAW_JSON,
        self::FORMAT_BODY_RAW_TEXT,
        self::FORMAT_BODY_RAW_CSV,
    );

    /**
     * Array of mapped mime types associated with given request formats
     *
     * @static array
     */
    public static $mimeTypeMapper = array(
        'multipart/form-data'               => self::FORMAT_BODY_FORM_DATA,
        'application/x-www-form-urlencoded' => self::FORMAT_BODY_URLENCODED,
        'application/json'                  => self::FORMAT_BODY_RAW_JSON,
        'text/plain'                        => self::FORMAT_BODY_RAW_TEXT,
        'text/csv'                          => self::FORMAT_BODY_RAW_CSV,
    );

    /**
     * Set Default Format
     *
     * @param string format
     * @return HandlerInterface
     */
    public function setDefaultFormat($defaultFormat)
    {
        $this->defaultFormat = $defaultFormat;

        return $this;
    }

    /**
     * Get Default Format
     *
     * @return string
     */
    public function getDefaultFormat()
    {
        return $this->defaultFormat;
    }

    public function getPostData(): object
    {
        $format     = $this->getRequestedFormat();
        $request    = $this->requestStack->getMasterRequest();
        $data       = null;

        switch ($format) {
            case self::FORMAT_BODY_FORM_DATA:
            case self::FORMAT_BODY_URLENCODED:
                $data = array_merge($request->request->all(), $request->files->all());
                break;
            case self::FORMAT_BODY_RAW_JSON:
            case self::FORMAT_BODY_RAW_TEXT:
                if (!empty($request->getContent())) {
                    try {
                        $contentData = (array) Json::parse($request->getContent());
                    } catch (\Exception $e) {
                        throw new \Exception($e->getMessage());
                    }
                } else {
                    $contentData = [];
                }

                $data = array_merge($contentData, $request->request->all(), $request->files->all());
                break;
        }

        return (object) $data;
    }

    /**
     * Determines request format based on request stack
     *
     * @return string
     */
    public function getRequestedFormat(): string
    {
        $request    = $this->requestStack->getMasterRequest();
        $type       = $request->headers->get('content_type', null);

        if (!empty($type)){
            return self::getFormat($type);
        }

        return $this->getDefaultFormat();
    }

    /**
     * Gets format based on known mime type
     *
     * @param   string  $mimeType
     * @return  string|null
     */
    public static function getFormat($mimeType)
    {
        $canonicalMimeType = $mimeType;

        if (false !== $pos = strpos($mimeType, ';')) {
            $canonicalMimeType = substr($mimeType, 0, $pos);
        }

        if (array_key_exists($canonicalMimeType, self::$mimeTypeMapper)) {
            return $canonicalMimeType;
        }

        return null;
    }

    /**
     * Gets filters parameter from requests
     *
     * @return array    List of filters parameters passed to Request
     */
    public function getFilters()
    {
        $request = $this->requestStack->getMasterRequest();

        return $request->query->get('filters', array());
    }

    /**
     * Gets sorts parameter from requests
     *
     * @return array    List of sorts parameters passed to Request
     */
    public function getSorts()
    {
        $request = $this->requestStack->getMasterRequest();

        return $request->query->get('sorts', array());
    }
}
