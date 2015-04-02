<?php

namespace SitePin\Component\Routing;

use SitePin\Component\Request;
use SitePin\Component\Routing\Exception\RouteNotFoundException;
use SitePin\Component\Routing\Exception\RoutingParseException;

class Router
{
    const YAML_PROPERTY_CONTROLLER = 'controller';
    const YAML_PROPERTY_PATH = 'path';
    const YAML_PROPERTY_METHOD = 'method';
    /**
     * @var array
     */
    private $routePaths;

    /**
     * @var array
     */
    private $routes;

    /**
     * @var SitePin\Component\Routing\RouteParser
     */
    private $parser;

    public function __construct(array $routePaths)
    {
        if (empty($routePaths)) {
            throw new RoutingNotFoundException('No route paths defined.');
        }

        $this->parser = new RouteParser();
        $this->routePaths = $routePaths;

        $this->load();
    }

    public function match($path)
    {
        $parts = explode('/', $path);

        foreach ($this->routes as $route) {
            if ($route->isMatch($parts)) {
                return $route;
            }
        }

        throw new RouteNotFoundException(
            sprintf(
                'Route matching "%s" not found.',
                $path
            )
        );
    }

    // TODO - generate urls from name and path arguments
    /*
    public function generateUrl($name, $args)
    {
        if (!$this->routes[$name]) {
            throw new RouteNotFoundException(
                sprintf(
                    'Route matching "%s" not found.',
                    $path
                )
            );
        }

        $route = $this->routes[$name];

        if ($route->getPathPartsCount() !== count($args)) {
            throw new RouteInsufficientArgumentsException(
                'Insufficient Arguments for Route "%s".',
                $route->getName()
            );
        }

        $parts = $route->getPathParts();
    }
    */

    /**
     * @return array
     */
    public function getRoutePaths()
    {
        return $this->routePaths;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param Route   $route
     * @param Request $request
     *
     */
    public function resolveArguments(Route $route, Request $request)
    {
        $args = array();
        $routePathParts = $route->getPathParts();
        $requestPathParts = explode('/', $request->getPath());

        foreach ($routePathParts as $key => $part) {
            if ($part !== Route::ARGUMENT) {
                continue;
            }

            $args[] = $requestPathParts[$key];
        }

        return $args;
    }

    /**
     * Loads routing information from the route paths
     */
    protected function load()
    {
        // Iterate over route paths
        foreach ($this->routePaths as $path) {
            $parsedRoutes = $this->parser->parseYaml($path);

            foreach ($parsedRoutes as $name => $properties) {
                // Verify routing controller property is provided
                if (!isset($properties[self::YAML_PROPERTY_CONTROLLER])) {
                    throw new RoutingParseException(
                        sprintf(
                            'Route "%s" missing "controller" property.',
                            $name
                        )
                    );
                }

                // Verify routing path property is provided
                if (!isset($properties[self::YAML_PROPERTY_PATH])) {
                    throw new RoutingParseException(
                        sprintf(
                            'Route "%s" missing "path" property.',
                            $name
                        )
                    );
                }

                // Check if routing method is provided
                // Default to 'GET'
                if (isset($properties[self::YAML_PROPERTY_METHOD])) {
                    $method = $properties[self::YAML_PROPERTY_METHOD];
                } else {
                    $method = 'GET';
                }

                // Add route
                $this->addRoute(
                    $name,
                    $properties[self::YAML_PROPERTY_CONTROLLER],
                    $properties[self::YAML_PROPERTY_PATH],
                    $method
                );
            }
        }

        if (empty($this->routes)) {
            throw new RoutingNotFoundException('No Routes found.');
        }
    }

    /**
     * @param string $name
     * @param string $controller
     * @param string $action
     * @param string $path
     * @param string $method
     */
    protected function addRoute($name, $controller, $path, $method)
    {
        list($newPath, $pathArgs) = $this->parser->parsePath($path);
        list($controllerName, $controllerMethod) = $this->parser->parseController($controller);

        $pathParts = explode('/', $newPath);
        $partCount = count($pathParts);

        $route = $this->createNewRoute()
            ->setName($name)
            ->setPathParts($pathParts)
            ->setPathPartsCount($partCount)
            ->setPathArgs($pathArgs)
            ->setControllerName($controllerName)
            ->setControllerMethod($controllerMethod)
            ->setMethod($method)
        ;

        $this->routes[$name] = $route;
    }

    protected function createNewRoute()
    {
        return new Route();
    }
}
