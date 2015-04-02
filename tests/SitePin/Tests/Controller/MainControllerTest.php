<?php

namespace SitePin\Tests\Controller;

use SitePin\Component\Response;
use SitePin\Controller\MainController;
use SitePin\Tests\SitePinTestCase;

class MainControllerTest extends SitePinTestCase
{
    private $controller;
    private $response;

    protected function setUp()
    {
        $this->controller = new MainControllerStub();

        $this->response = $this->buildMock('SitePin\Component\Response');
        $this->controller->setResponse($this->response);
    }

    public function testIndex()
    {
        // Call Test Method
        $response = $this->controller->index();

        // Assertions
        $this->assertInstanceOf(
            'SitePin\Component\Response',
            $response
        );
    }

    public function testAbout()
    {
        // Call Test Method
        $response = $this->controller->about();

        // Assertions
        $this->assertInstanceOf(
            'SitePin\Component\Response',
            $response
        );
    }
}

class MainControllerStub extends MainController
{
    /**
     * @var SitePin\Component\Response
     */
    private $response;

    public function renderResponse($template, array $arguments = array(), $code = 200, array $headers = null)
    {
        return $this->response;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
