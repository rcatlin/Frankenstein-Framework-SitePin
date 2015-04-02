<?php

namespace SitePin\Tests\Component;

use SitePin\Component\Routing\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testSettersAndGetters()
    {
        $name = 'name';
        $pathParts = array(
            'part0',
            'part1',
            Route::ARGUMENT,
        );
        $pathPartsCount = 2;
        $pathArgs = array('arg0');
        $controllerName = 'controller-name';
        $controllerMethod = 'controller-method';
        $method = 'GET';

        $route = new Route();

        $route
            ->setName($name)
            ->setPathParts($pathParts)
            ->setPathPartsCount($pathPartsCount)
            ->setPathArgs($pathArgs)
            ->setControllerName($controllerName)
            ->setControllerMethod($controllerMethod)
            ->setMethod($method)
        ;

        $this->assertEquals($route->getName(), $name);
        $this->assertEquals($route->getPathParts(), $pathParts);
        $this->assertEquals($route->getPathPartsCount(), $pathPartsCount);
        $this->assertEquals($route->getPathArgs(), $pathArgs);
        $this->assertEquals($route->getControllerName(), $controllerName);
        $this->assertEquals($route->getControllerMethod(), $controllerMethod);
        $this->assertEquals($route->getMethod(), $method);
    }

    /**
     * @dataProvider isMatchProvider
     */
    public function testIsMatch($pathParts, $parts, $expectation)
    {
        $pathPartsCount = count($pathParts);

        $route = new Route();
        $route
            ->setPathParts($pathParts)
            ->setPathPartsCount($pathPartsCount)
        ;

        $this->assertEquals(
            $route->isMatch($parts),
            $expectation
        );
    }

    public function isMatchProvider()
    {
        $pathParts0 = array(
            'bookmark',
            Route::ARGUMENT,
            'edit',
        );

        return array(
            array(
                $pathParts0,
                array(
                    'bookmark',
                    '6',
                    'edit',
                ),
                true,
            ),
            array(
                $pathParts0,
                array(
                    'bookmark',
                    '6',
                    'create',
                ),
                false,
            ),
            array(
                $pathParts0,
                array(),
                false,
            ),
            array(
                array(),
                array(),
                true,
            ),
            array(
                array(
                    Route::ARGUMENT,
                ),
                array(
                    'wildcard',
                ),
                true,
            ),
        );
    }
}
