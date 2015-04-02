<?php

namespace SitePin\Tests\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use SitePin\Component\Response;
use SitePin\Component\Request;
use SitePin\Controller\BookmarkController;
use SitePin\Tests\SitePinTestCase;

class BookmarkControllerTest extends SitePinTestCase
{
    private $controller;
    private $response;
    private $em;
    private $bookmarkRepo;

    protected function setUp()
    {
        $this->controller = new BookmarkControllerStub();

        $this->response = $this->buildMock('SitePin\Component\Response');
        $this->em = $this->buildMock('Doctrine\ORM\EntityManager');
        $this->bookmarkRepo = $this->buildMock('Doctrine\ORM\EntityRepository');

        $this->controller->setResponse($this->response);
        $this->controller->setEntityManager($this->em);
        $this->controller->setBookmarkRepository($this->bookmarkRepo);
    }

    public function testShow()
    {
        $page = 2;
        $limit = 5;
        $expectedOffset = 5;

        $request = new Request(
            'GET',
            'path',
            array(
                BookmarkController::QUERY_PAGE_PARAM => $page,
                BookmarkController::QUERY_LIMIT_PARAM => $limit,
            ),
            array()
        );

        $this->controller->setRequest($request);
        $builder = $this->buildMock('Doctrine\ORM\QueryBuilder');
        $query = $this->buildAbstractMock('Doctrine\ORM\AbstractQuery');
        $bookmarks = array('bookmarks');

        // Expectations
        $this->bookmarkRepo->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($builder)
        ;

        $builder->expects($this->once())
            ->method('setFirstResult')
            ->with($expectedOffset)
            ->willReturn($builder)
        ;
        $builder->expects($this->once())
            ->method('setMaxResults')
            ->with($limit)
            ->willReturn($builder)
        ;
        $builder->expects($this->once())
            ->method('getQuery')
            ->willReturn($query)
        ;
        $query->expects($this->once())
            ->method('execute')
            ->willReturn($bookmarks)
        ;

        // Call Test Method
        $response = $this->controller->show();

        // Assertiosn
        $this->assertInstanceOf(
            'SitePin\Component\Response',
            $response
        );
    }
}

class BookmarkControllerStub extends BookmarkController
{
    /**
     * @var SitePin\Component\Request
     */
    private $request;

    /**
     * @var SitePin\Component\Response
     */
    private $response;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var Doctrine\ORM\EntityRepository
     */
    private $bookmarkRepo;

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function renderResponse($template, array $arguments = array(), $code = 200, array $headers = null)
    {
        return $this->response;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    protected function getEntityManager()
    {
        return $this->em;
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    protected function getBookmarkRepository()
    {
        return $this->bookmarkRepo;
    }

    public function setBookmarkRepository(EntityRepository $bookmarkRepo)
    {
        $this->bookmarkRepo = $bookmarkRepo;
    }
}
