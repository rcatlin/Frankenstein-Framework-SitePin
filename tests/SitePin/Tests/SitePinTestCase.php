<?php

namespace SitePin\Tests;

class SitePinTestCase extends \PHPUnit_Framework_TestCase
{
    protected function buildMock($class)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    protected function buildAbstractMock($class)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods(
                get_class_methods($class)
            )
            ->getMockForAbstractClass()
        ;   
    }
}
