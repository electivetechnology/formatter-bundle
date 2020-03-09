<?php

namespace Elective\FormatterBundle\Tests\Request;

use Elective\FormatterBundle\Request\Handler as RequestHandler;
use Elective\FormatterBundle\Request\HandlerInterface;
use Elective\FormatterBundle\Parsers\Json as JsonParser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Elective\FormatterBundle\Tests\Request\HandlerTest
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class HandlerTest extends TestCase
{
    public function testGetRequestedFormat()
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request);

        $requestHandler = new RequestHandler($requestStack);
        $ret = $requestHandler->getRequestedFormat();

        $this->assertSame($requestHandler->getDefaultFormat(), $ret);

        $requestStack = new RequestStack();
        $request = new Request;
        $request->headers->set('Accept', 'text/plain');
        $requestStack->push($request);

        $requestHandler = new RequestHandler($requestStack);
        $ret = $requestHandler->getRequestedFormat();

        $this->assertSame('text/plain', $ret);
    }

    /**
     * @dataProvider getFormat
     */
    public function testGetFormat($expected, $mimeType)
    {
        $this->assertSame($expected, RequestHandler::getFormat($mimeType));
    }

    public function getFormat()
    {
        return array(
            array('application/json', 'application/json'),
            array(RequestHandler::FORMAT_BODY_FORM_DATA, RequestHandler::FORMAT_BODY_FORM_DATA),
            array(RequestHandler::FORMAT_BODY_URLENCODED, RequestHandler::FORMAT_BODY_URLENCODED),
            array(RequestHandler::FORMAT_BODY_RAW_TEXT, RequestHandler::FORMAT_BODY_RAW_TEXT),
            array(null, 'application/json2'),
        );
    }

    /**
     * @dataProvider getPostDataProvider
     */
    public function testGetPostData($requestHandler, $expectedData)
    {
        $this->assertEquals($expectedData, $requestHandler->getPostData());
    }

    public function getPostDataProvider()
    {
        $data = array();

        // First round
        $object = new \StdClass();
        $object->username = "Jane";
        $object->password = "AbCdEf";

        $parameters = get_object_vars($object);

        $request = new Request;
        $request->headers->set('accept', RequestHandler::FORMAT_BODY_FORM_DATA);

        foreach ($parameters as $key => $value) {
            $request->request->set($key, $value);
        }

        $requestStack = new RequestStack;
        $requestStack->push($request);

        $requestHandler = new RequestHandler($requestStack);

        $data[] = array($requestHandler, $object);

        // Second round
        $object = new \StdClass();
        $object->name   = "Jane";
        $object->email  = "jane@example.com";

        $request = Request::create(
            '/',
            'GET',
            array(),
            array(),
            array(),
            array(),
            JsonParser::format($object)
        );

        $request->headers->set('accept', RequestHandler::FORMAT_BODY_RAW_JSON);

        $requestStack = new RequestStack;
        $requestStack->push($request);

        $requestHandler = new RequestHandler($requestStack);

        $data[] = array($requestHandler, $object);

        return $data;
    }

    /**
     * @dataProvider getPostDataFailProvider
     * @expectedException   Exception
     */
    public function testGetPostDataFail($data)
    {
        $request = Request::create(
            '/',
            'GET',
            array(),
            array(),
            array(),
            array(),
            $data
        );

        $request->headers->set('accept', RequestHandler::FORMAT_BODY_RAW_JSON);

        $requestStack = new RequestStack;
        $requestStack->push($request);

        $requestHandler = new RequestHandler($requestStack);

        $this->assertEquals($requestHandler->getPostData(), $expectedData);
    }

    public function getPostDataFailProvider()
    {
        return array(
            array("{invalikd:jSOn}"),
            array('{"Json": [{"invalid"}]}'),
        );
    }
}
