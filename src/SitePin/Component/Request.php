<?php

namespace SitePin\Component;

class Request
{
    /**
     * @var array
     */
    public $server;

    /**
     * @var array
     */
    public $query;

    /**
     * @var array
     */
    public $request;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $path;

    /**
     * @var Purl\Url
     */
    private $purl;

    public function __construct(
        $method,
        $path,
        array $query,
        array $request
    ) {
        $this->method = $method;
        $this->path = $path;
        $this->query = $query;
        $this->request = $request;
    }

    public static function createFromGlobals($server, $get, $post)
    {
        return new self(
            $server['REQUEST_METHOD'],
            $server['SCRIPT_NAME']
            .(!isset($server['PATH_INFO']) ? '' : $server['PATH_INFO']),
            $get,
            $post
        );
    }

    /**
     * @return $string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return $string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getQueryParameter($name)
    {
        if (!isset($this->query[$name])) {
            return;
        }

        return $this->query[$name];
    }

    public function getRequestParameter($name)
    {
        if (!isset($this->request[$name])) {
            return;
        }

        return $this->request[$name];
    }
}
