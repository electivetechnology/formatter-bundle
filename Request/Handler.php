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
            FORMAT_BODY_RAW_CSV         = 'text/csv',
            FORMAT_BODY_RAW_HTML        = 'text/html';

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
        'multipart/form-data'                               => self::FORMAT_BODY_FORM_DATA,
        'application/x-www-form-urlencoded'                 => self::FORMAT_BODY_URLENCODED,
        'application/json'                                  => self::FORMAT_BODY_RAW_JSON,
        'text/plain'                                        => self::FORMAT_BODY_RAW_TEXT,
        'text/csv'                                          => self::FORMAT_BODY_RAW_CSV,
        'text/html,application/xhtml+xml,application/xml'   => self::FORMAT_BODY_RAW_HTML,
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

    public function getPostData(): \StdClass
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
        $types      = $request->headers->get('accept', '*/*');

        if ($types != '*/*'){
            return self::getFormat($types);
        }

        return $this->getDefaultFormat();
    }

    /**
     * Gets format based on known mime type
     *
     * @param   string  $mimeTypes
     * @return  string|null
     */
    public static function getFormat($mimeTypes)
    {
        $canonicalMimeType = $mimeTypes;

        if (false !== $pos = strpos($mimeTypes, ';')) {
            $canonicalMimeType = substr($mimeTypes, 0, $pos);
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
        // Get master request
        $request = $this->requestStack->getMasterRequest();

        // Lets process query filters first
        $filters = $request->query->get('filters', array());

        $obj = json_decode($request->getContent());

        if (!is_null($obj) && isset($obj->query)) {
            // Parse query into array of keys
            parse_str(htmlspecialchars_decode($obj->query), $query);

            // Get filters if any
            if (isset($query['filters']) && is_array($query['filters'])) {
                foreach ($query['filters'] as $filter) {
                    $filters[] = $filter;
                }
            }
        }

        return $filters;
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
