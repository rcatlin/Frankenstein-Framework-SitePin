<?php

namespace SitePin\Component;

use League\Container\Container;

class Controller
{
    /**
     * @var League\Container\Container
     */
    private $container;

    /**
     * @param League\Container\Container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return League\Container\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return SitePin\Component\Request
     */
    public function getRequest()
    {
        return $this->container->get('request');
    }

    /**
     * @param string $path
     *
     * @return SitePin\Component\Response
     */
    public function redirect($path)
    {
        return $this->getResponse(
            '',
            302,
            array('Location: '.$path)
        );
    }

    /**
     * @param string  $template
     * @param array   $arguments
     * @param integer $code
     * @param array   $headers
     *
     * @return SitePin\Component\Response
     */
    public function renderResponse($template, array $arguments = array(), $code = 200, array $headers = null)
    {
        return $this->getResponse(
            $this->render(
                $template,
                $arguments
            ),
            $code,
            $headers
        );
    }

    /**
     * @param string $template
     * @param array  $arguments
     *
     * @return SitePin\Component\Response
     */
    public function render($template, array $arguments = array())
    {
        $twig = $this->getContainer()->get('twig');

        return $twig->render(
            $template,
            $arguments
        );
    }

    /**
     * @param string  $content
     * @param integer $code
     * @param array   $headers
     *
     * @return SitePin\Component\Response
     */
    public function getResponse($content, $code = 200, array $headers = null)
    {
        if (!is_array($headers)) {
            $headers = array();
        }
        $headers[] = 'Content-Type: text/html';

        return new Response(
            $content,
            $headers,
            $code
        );
    }

    /**
     * @param array   $json
     * @param integer $code
     * @param array   $headers
     *
     * @return SitePin\Component\Response
     */
    public function getJsonResponse(array $json, $code = 200, array $headers = null)
    {
        if (!is_array($headers)) {
            $headers = array();
        }
        $headers[] = 'Content-Type: application/json';

        return new Response(
            json_encode($json),
            $headers,
            $code
        );
    }
}
