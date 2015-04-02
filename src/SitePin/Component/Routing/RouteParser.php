<?php

namespace SitePin\Component\Routing;

use SitePin\Component\Routing\Exception\RouteControllerInvalidException;
use SitePin\Component\Routing\Exception\RoutePathInvalidException;
use Symfony\Component\Yaml\Yaml;

class RouteParser
{
    const PATH_ARGUMENT_REGEX = '/\{[a-zA-Z]{1}[a-zA-Z0-9]*\}/';
    const PATH_VALID_CHARACTERS_REGEX = '/^\/[a-zA-Z0-9\/\-\_\{\}]*$/';
    const CONTROLLER_REGEX = '/^([a-zA-Z]{1}[a-zA-Z0-9_\-]*):([a-zA-Z]{1}[a-zA-Z0-9]*)$/';

    /**
     * @param string $controller
     *
     * @return array
     */
    public function parseController($controller)
    {
        $matches = array();
        preg_match(self::CONTROLLER_REGEX, $controller, $matches);

        if (empty($matches) || count($matches) !== 3) {
            throw new RouteControllerInvalidException(
                sprintf(
                    'Invalid route "controller" property: %s',
                    $controller
                )
            );
        }

        return array(
            $matches[1],
            $matches[2],
        );
    }

    /**
     * @param string $path
     *
     * @return array
     */
    public function parsePath($path)
    {
        // Check if valid
        if (!$this->isPathValid($path)) {
            throw new RoutePathInvalidException(
                sprintf(
                    'Invalid route "path" property: %s',
                    $path
                )
            );
        }

        $arguments = array();
        preg_match_all(self::PATH_ARGUMENT_REGEX, $path, $arguments);

        if (empty($arguments)) {
            return array(
                $path,
                array(),
            );
        }

        return array(
            preg_replace(self::PATH_ARGUMENT_REGEX, Route::ARGUMENT, $path),
            $arguments,
        );
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function parseYaml($filePath)
    {
        return Yaml::parse(
            file_get_contents($filePath)
        );
    }

    /**
     * @param string $path
     *
     * @return boolean
     */
    protected function isPathValid($path)
    {
        return preg_match(self::PATH_VALID_CHARACTERS_REGEX, $path);
    }
}
