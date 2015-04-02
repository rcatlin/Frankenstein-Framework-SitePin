<?php

namespace SitePin\Tests\Model;

use SitePin\Model\Bookmark;

class BookmarkTest extends \PHPUnit_Framework_TestCase
{
    public function testSettersAndGetters()
    {
        $bookmark = new Bookmark();

        $uri = 'http://uri.com/';
        $name = 'bookmark-name';

        $bookmark
            ->setUri($uri)
            ->setName($name)
        ;

        $this->assertEquals($bookmark->getUri(), $uri);
        $this->assertEquals($bookmark->getName(), $name);
    }
}
