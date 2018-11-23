<?php

namespace Elective\FormatterBundle\Tests\Response;

use PHPUnit\Framework\TestCase;
use Elective\FormatterBundle\Response\Formatter as ResponseFormatter;
use Elective\FormatterBundle\Response\FormatterInterface;
use Elective\FormatterBundle\Parsers\Json as JsonParser;
use Symfony\Component\HttpFoundation\Response;

/**
 * Elective\FormatterBundle\Tests\Response\Formatter\FormatterTest
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class FormatterTest extends TestCase
{
    public function testRenderPromise()
    {
        $responseFormatter = new ResponseFormatter;

        $ret = $responseFormatter->render();

        // test render method will return Promise
        $this->assertInstanceOf(Response::class, $ret);
    }

    /**
     * Test correct status set
     *
     * @dataProvider correctStatusProvider
     */
    public function testRenderCorrectStatus($suppliedStatus, $expectedStatus)
    {
        $responseFormatter = new ResponseFormatter;

        $ret = $responseFormatter->render(null, $suppliedStatus);

        $this->assertEquals($expectedStatus, $ret->getStatusCode());
    }

    public function correctStatusProvider()
    {
        return array(
            array(null, Response::HTTP_OK),
            array(Response::HTTP_OK, Response::HTTP_OK),
            array(Response::HTTP_CREATED, Response::HTTP_CREATED),
            array(Response::HTTP_BAD_REQUEST, Response::HTTP_BAD_REQUEST),
        );
    }

    /**
     * Test correct headers are set
     *
     * @dataProvider headersProvider
     */
    public function testRenderCorrectHeaders($headers)
    {
        $responseFormatter = new ResponseFormatter;

        $ret = $responseFormatter->render(null, null, $headers);

        // test headers are present in the Response object
        foreach ($headers as $header) {
            $this->assertEquals(current($header), $ret->headers->get(key($header), current($header)));
        }
    }

    public function headersProvider()
    {
        return array(
            array(
                array(['Content-Type' => 'application/json']),
                array(['Access-Control-Allow-Headers' => 'Content-Type, X-Auth-Token, X-App-Token']),
                array(['X-Server-Name' => 'ubuntu-18.04']),
                array(
                    ['Content-Type' => 'text/html'],
                    ['X-Debug-Token' => '6771a4']
                ),
            ),
        );
    }

    /**
     * Test correct headers are set
     *
     * @dataProvider setHeadersProvider
     */
    public function testSetHeaders($headers)
    {
        $responseFormatter = new ResponseFormatter;

        // Set Headers
        $this->assertInstanceOf(FormatterInterface::class, $responseFormatter->setHeaders($headers));
        $responseHeaders = $responseFormatter->getHeaders();

        foreach ($headers as $key => $header) {
            if (is_array($header)) {
                foreach ($header as $value) {
                    $this->assertContains($value, $responseHeaders);
                }
            } else {
                $this->assertTrue(array_key_exists($key, $responseHeaders));
                $this->assertEquals($header, $responseHeaders[$key]);
            }
        }
    }

    public function setHeadersProvider()
    {
        return array(
            array(
                array(['Content-Type' => 'application/json', 'X-Debug-Token' => '6771a4']),
            ),
            array(['X-Expires-In' => 123]),
        );
    }

    /**
     * Test correct headers are added
     *
     * @dataProvider addHeadersProvider
     */
    public function testAddHeader($key, $value)
    {
        $responseFormatter = new ResponseFormatter;

        // Set Headers
        $this->assertInstanceOf(FormatterInterface::class, $responseFormatter->addHeader($key, $value));

        // Check array contains requested headers
        $headers = $responseFormatter->getHeaders();

        $this->assertArrayHasKey($key, $headers);
        $this->assertEquals($value, $headers[$key]);
    }

    public function addHeadersProvider()
    {
        return array(
            array('Content-Type', 'application/json'),
            array('Content-Type', 'text/html'),
            array('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, X-App-Token'),
        );
    }

    /**
     * Test correct parsers are set and removed
     *
     * @dataProvider setParsersProvider
     */
    public function testSetAndRemoveParsers($parsers)
    {
        $responseFormatter = new ResponseFormatter;

        // Set Parsers
        $this->assertInstanceOf(FormatterInterface::class, $responseFormatter->setParsers($parsers));

        $this->assertEquals($parsers, $responseFormatter->getParsers());

        foreach ($responseFormatter->getParsers() as $key => $parserToBeRemoved) {
            $this->assertInstanceOf(FormatterInterface::class, $responseFormatter->removeParser($key));
            $this->assertArrayNotHasKey($key, $responseFormatter->getParsers());
        }
    }

    public function setParsersProvider()
    {
        $jsonParser = new JsonParser;

        return array(
            array(['application/json' => $jsonParser, 'text/json' => $jsonParser]),
        );
    }

    /**
     * Test correct parsers are added
     *
     * @dataProvider addParsersProvider
     */
    public function testAddParsers($contentType, $parser)
    {
        $responseFormatter = new ResponseFormatter;

        // Add Parser
        $this->assertInstanceOf(FormatterInterface::class, $responseFormatter->addParser($contentType, $parser));

        $this->assertArrayHasKey($contentType, $responseFormatter->getParsers());
    }

    public function addParsersProvider()
    {
        $jsonParser = new JsonParser;

        return array(
            array('application/json', $jsonParser),
            array('text/json', $jsonParser),
        );
    }

    /**
     * Test set default parser
     *
     * @dataProvider parsersProvider
     */
    public function testSetDefaultParser($parser)
    {
        $responseFormatter = new ResponseFormatter;

        // Add Parser
        $this->assertInstanceOf(FormatterInterface::class, $responseFormatter->setDefaultParser($parser));

        $this->assertSame($parser, $responseFormatter->getDefaultParser());
    }

    public function parsersProvider()
    {
        $jsonParser = new JsonParser;

        return array(
            array($jsonParser),
        );
    }
}
