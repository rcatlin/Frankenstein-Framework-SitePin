<?php

namespace SitePin\Component\Routing;

class Route
{
    const ARGUMENT = '?';

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $pathParts;

    /**
     * @var integer
     */
    private $pathPartsCount;

    /**
     * @var array
     */
    private $pathArgs;

    /**
     * @var string
     */
    private $controllerName;

    /**
     * @var string
     */
    private $controllerMethod;

    /**
     * @var string
     */
    private $method;

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPathParts($pathParts)
    {
        $this->pathParts = $pathParts;

        return $this;
    }

    public function getPathParts()
    {
        return $this->pathParts;
    }

    public function setPathPartsCount($pathPartsCount)
    {
        $this->pathPartsCount = intval($pathPartsCount);

        return $this;
    }

    public function getPathPartsCount()
    {
        return $this->pathPartsCount;
    }

    public function setPathArgs(array $pathArgs = null)
    {
        $this->pathArgs = $pathArgs;

        return $this;
    }

    public function getPathArgs()
    {
        return $this->pathArgs;
    }

    public function setControllerName($controllerName)
    {
        $this->controllerName = $controllerName;

        return $this;
    }

    public function getControllerName()
    {
        return $this->controllerName;
    }

    public function setControllerMethod($controllerMethod)
    {
        $this->controllerMethod = $controllerMethod;

        return $this;
    }

    public function getControllerMethod()
    {
        return $this->controllerMethod;
    }

    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param array $parts
     *
     * @return boolean
     */
    public function isMatch(array $parts)
    {
        $count = $this->getPathPartsCount();

        if (count($parts) !== $count) {
            return false;
        }

        $selfParts = $this->getPathParts();
        for ($i = 0; $i < $count; $i++) {
            if (!isset($parts[$i]) || !isset($selfParts[$i])) {
                return false;
            }

            if ($selfParts[$i] == self::ARGUMENT) {
                continue;
            }

            if ($selfParts[$i] !== $parts[$i]) {
                return false;
            }
        }

        return true;
    }

    public function toArray()
    {
        return array(
            'name' => $this->getName(),
            'pathParts' => $this->getPathParts(),
            'pathPartsCount' => $this->getPathPartsCount(),
            'pathArgs' => $this->getPathArgs(),
            'controllerName' => $this->getControllerName(),
            'controllerMethod' => $this->getControllerMethod(),
            'method' => $this->getMethod(),
        );
    }
}
