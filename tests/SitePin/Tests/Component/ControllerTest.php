<?php

namespace SitePin\Tests\Component;

use League\Container\Container;
use SitePin\Tests\SitePinTestCase;
use SitePin\Component\Controller;

class ControllerTest extends SitePinTestCase
{
    private $controller;

    protected function setUp()
    {
        $this->controller = new Controller();
    }

    public function testSetGetContainer()
    {
        $container = $this->buildMock('League\Container\Container');

        $this->controller->setContainer($container);

        $this->assertEquals(
            $container,
            $this->controller->getContainer()
        );
    }

    public function testGetRequest()
    {
        $container = $this->buildMock('League\Container\Container');
        $request = $this->buildMock('SitePin\Component\Request');

        $this->controller->setContainer($container);

        // Expectations
        $container->expects($this->once())
            ->method('get')
            ->with('request')
            ->willReturn($request)
        ;

        // Call Test Method
        $result = $this->controller->getRequest();

        // Assertions
        $this->assertEquals(
            $request,
            $result
        );
    }

    public function testRedirect()
    {
        // Call Test Method
        $response = $this->controller->redirect('path');

        // Assertions
        $this->assertEquals(
            302,
            $response->getCode()
        );

        $this->assertEquals(
            array(
                'Location: path',
                'Content-Type: text/html',
            ),
            $response->getHeaders()
        );

        $this->assertEquals(
            '',
            $response->getContent()
        );
    }

    public function testRenderResponse()
    {
        $twig = $this->buildMock('Twig_Environment');
        $response = $this->buildMock('SitePin\Component\Response');

        $container = new Container();
        $container->add('twig', $twig);
        $this->controller->setContainer($container);

        $template = 'template-name';
        $arguments = array(
            'name0' => 'val0',
        );
        $code = 202;
        $headers = array(
            'Random' => '0123456789',
        );

        $rendered = 'rendered by twig';

        // Expectations
        $twig->expects($this->once())
            ->method('render')
            ->with(
                $template,
                $arguments
            )
            ->willReturn($rendered)
        ;

        // Call Test Method
        $response = $this->controller->renderResponse(
            $template,
            $arguments,
            $code,
            $headers
        );

        // Assertions
        $this->assertEquals(
            $rendered,
            $response->getContent()
        );

        $this->assertEquals(
            $code,
            $response->getCode()
        );

        $this->assertEquals(
            array(
                'Random' => '0123456789',
                'Content-Type: text/html',
            ),
            $response->getHeaders()
        );
    }

    public function testGetJsonResponse()
    {
        // Call Test Method
        $response = $this->controller->getJsonResponse(
            array('json' => 'response')
        );

        // Assertions
        $this->assertEquals(
            200,
            $response->getCode()
        );

        $this->assertEquals(
            '{"json":"response"}',
            $response->getContent()
        );

        $this->assertEquals(
            array(
                'Content-Type: application/json',
            ),
            $response->getHeaders()
        );
    }

    public function testGetResponse()
    {
        // Call Test Method
        $response = $this->controller->getResponse(
            'content'
        );

        // Assertions
        $this->assertEquals(
            200,
            $response->getCode()
        );

        $this->assertEquals(
            array(
                'Content-Type: text/html',
            ),
            $response->getHeaders()
        );

        $this->assertEquals(
            'content',
            $response->getContent()
        );
    }

    public function testRender()
    {
        $twig = $this->buildMock('Twig_Environment');
        $response = $this->buildMock('SitePin\Component\Response');

        $container = new Container();
        $container->add('twig', $twig);
        $this->controller->setContainer($container);

        $template = 'template-name';
        $arguments = array(
            'name0' => 'val0',
            'name1' => 'val1',
        );

        // Expectations
        $twig->expects($this->once())
            ->method('render')
            ->with(
                $template,
                $arguments
            )
            ->willReturn($response)
        ;

        // Call Test Method
        $result = $this->controller->render($template, $arguments);

        // Assertions
        $this->assertEquals(
            $response,
            $result
        );
    }
}
