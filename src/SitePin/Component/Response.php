<?php

namespace SitePin\Component;

class Response
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var integer
     */
    private $code;

    /**
     * @param string
     * @param array
     * @param integer
     */
    public function __construct($content = '', array $headers = null, $code = 200)
    {
        $this->content = (string) $content;
        $this->headers = (isset($headers)) ? $headers : array();
        $this->code = intval($code);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function send()
    {
        $this->setHeaders();
        $this->outputContent();
    }

    protected function setHeaders()
    {
        header(
            sprintf(
                'HTTP 1.1/',
                $this->getCode()
            )
        );

        foreach ($this->getHeaders() as $header) {
            header($header);
        }
    }

    protected function outputContent()
    {
        echo $this->getContent();
    }
}
