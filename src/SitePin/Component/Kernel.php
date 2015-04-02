<?php

namespace SitePin\Component;

use League\Container\Container;
use SitePin\Component\Routing\Router;
use SitePin\Component\Routing\Route;

class Kernel
{
    /**
     * @var League\Container\Container;
     */
    private $container;

    public function __construct()
    {
        $this->initContainer();
        $this->initRouter();
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        $this->container->add('request', $request);

        $router = $this->container->get('router');

        $route = $router->match($request->getPath());

        $controllerName = $route->getControllerName();
        $controllerMethod = $route->getControllerMethod();

        $controller = $this->getController($controllerName);

        if (
            !in_array(
                $controllerMethod,
                get_class_methods($controller)
            )
        ) {
            throw new ControllerMethodNotFoundException(
                sprintf(
                    '%s does not have method "%s"',
                    get_class($controller),
                    $controllerMethod
                )
            );
        }

        $args = $router->resolveArguments(
            $route,
            $request
        );

        return call_user_func_array(
            array(
                $controller,
                $controllerMethod,
            ),
            $args
        );
    }

    /**
     * @return League/Container/Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Initializes Router
     */
    protected function initRouter()
    {
        $this->container
            ->add(
                'router',
                'SitePin\Component\Routing\Router'
            )
            ->withArgument(
                array(
                    __DIR__.'/../Resources/config/routing.yml',
                )
            )
        ;
    }

    protected function initContainer()
    {
        $this->container = new Container();
    }

    /**
     * Finds a controller by name, constructs that controller,
     * and returns the controller.
     *
     * @param string $name
     *
     * @return object
     *
     * @throws ControllerNotFoundException
     */
    protected function getController($name)
    {
        $class = sprintf(
            'SitePin\Controller\%sController',
            $name
        );

        if (!class_exists($class)) {
            throw new ControllerNotFoundException(
                sprintf(
                    'Controller "%s" not found.',
                    $name
                )
            );
        }

        $controller = new $class();

        $controller->setContainer($this->getContainer());

        return $controller;
    }
}
