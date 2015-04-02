<?php

namespace SitePin\Controller;

use SitePin\Component\Controller;
use SitePin\Model\Bookmark;

class BookmarkController extends Controller
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = 10;
    const QUERY_PAGE_PARAM = 'page';
    const QUERY_LIMIT_PARAM = 'limit';

    /**
     * List paginated Bookmarks
     *
     * @return SitePin\Component\Response
     */
    public function show()
    {
        $request = $this->getRequest();

        // Get 'page' Query Parameter
        $page = $request->getQueryParameter(self::QUERY_PAGE_PARAM);
        if ($page === null) {
            $page = self::DEFAULT_PAGE;
        } elseif ($page <= 0) {
            return $this->redirect('/bookmarks');
        }

        // Get 'limit' Query Parameter
        $limit = $request->getQueryParameter(self::QUERY_LIMIT_PARAM);
        if ($limit == null || $limit <= 0) {
            $limit = self::DEFAULT_LIMIT;
        }

        // Calculate offset
        $offset = $limit * ($page - 1);

        // Get bookmarks
        $bookmarks = $this->getBookmarkRepository()
            ->createQueryBuilder('b')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->execute()
        ;

        return $this->renderResponse(
            'Bookmark/list.html.twig',
            array(
                'bookmarks' => $bookmarks,
                'nextUrl' => sprintf(
                    '/bookmarks?page=%s',
                    $page + 1
                ),
                'previousUrl' => sprintf(
                    '/bookmarks?page=%s',
                    $page - 1
                ),
                'page' => $page,
                'currPage' => 'bookmarks',
            )
        );
    }

    /**
     * View a Bookmark by Id
     *
     * @param $id string
     *
     * @return SitePin\Component\Response
     */
    public function view($id)
    {
        $bookmark = $this->getBookmarkById($id);

        if ($bookmark === null) {
            return $this->renderResponse(
                'message.html.twig',
                array(
                    'message' => 'Bookmark not found.',
                )
            );
        }

        return $this->renderResponse(
            'Bookmark/view.html.twig',
            array(
                'bookmark' => $bookmark,
            )
        );
    }

    /**
     * Create a Bookmark
     *
     * @return SitePin\Component\Response
     */
    public function create()
    {
        return $this->renderResponse(
            'Bookmark/form.html.twig',
            array(
                'actionName' => 'Create',
                'formAction' => '/bookmark/save',
                'currPage' => 'create',
            )
        );
    }

    /**
     * Edit a Bookmark
     *
     * @param string $id
     *
     * @return SitePin\Component\Response
     */
    public function edit($id)
    {
        $bookmark = $this->getBookmarkById($id);

        if ($bookmark === null) {
            return $this->renderResponse(
                'message.html.twig',
                array(
                    'message' => 'Bookmark not found.',
                )
            );
        }

        return $this->renderResponse(
            'Bookmark/form.html.twig',
            array(
                'actionName' => 'Edit',
                'bookmark' => $bookmark,
                'formAction' => sprintf(
                    '/bookmark/%s/update',
                    $id
                ),
            )
        );
    }

    /**
     * Save a new Bookmark
     *
     * @return SitePin\Component\Response
     */
    public function save()
    {
        // Get Request
        $request = $this->getRequest();

        // Submitted Parameters
        $params = $request->request;
        $uri = trim($request->getRequestParameter('uri'));
        $name = trim($request->getRequestParameter('name'));

        if ($uri === null || empty($uri)) {
            return $this->renderResponse(
                'message.html.twig',
                array(
                    'message' => 'Missing Bookmark uri.',
                )
            );
        } elseif ($name === null || empty($name)) {
            return $this->renderResponse(
                'message.html.twig',
                array(
                    'message' => 'Missing Bookmark name.',
                )
            );
        }

        // New Bookmark
        $bookmark = $this->getNewBookmark()
            ->setUri($params['uri'])
            ->setName($params['name'])
        ;

        $em = $this->getEntityManager();
        $em->persist($bookmark);
        $em->flush();

        return $this->redirect(
            sprintf(
                '/bookmark/%s',
                $bookmark->getId()
            )
        );
    }

    /**
     * Update an existing Bookmark
     *
     * @param string $id
     *
     * @return SitePin\Component\Response
     */
    public function update($id)
    {
        // Get Request
        $request = $this->getRequest();

        // Submitted Parameters
        $params = $request->request;
        $uri = trim($request->getRequestParameter('uri'));
        $name = trim($request->getRequestParameter('name'));

        if ($uri === null || empty($uri)) {
            return $this->renderResponse(
                'message.html.twig',
                array(
                    'message' => 'Missing Bookmark uri.',
                )
            );
        } elseif ($name === null || empty($name)) {
            return $this->renderResponse(
                'message.html.twig',
                array(
                    'message' => 'Missing Bookmark name.',
                )
            );
        }

        $bookmark = $this->getBookmarkById($id);

        if ($bookmark === null) {
            return $this->renderResponse(
                'message.html.twig',
                array(
                    'message' => 'Bookmark to update not found.',
                )
            );
        }

        $bookmark
            ->setUri($uri)
            ->setName($name)
        ;

        $em = $this->getEntityManager();

        $em->flush($bookmark);

        return $this->redirect(
            sprintf(
                '/bookmark/%s',
                $bookmark->getId()
            )
        );
    }

    /**
     * Delete a Bookmark
     * @param string $id
     *
     * @return SitePin\Component\Response
     */
    public function delete($id)
    {
        $bookmark = $this->getBookmarkById($id);

        if ($bookmark === null) {

            return $this->renderResponse(
                'message.html.twig',
                array(
                    'message' => 'Bookmark to delete not found.',
                )
            );
        }

        $em = $this->getEntityManager();
        $em->remove($bookmark);
        $em->flush();

        return $this->renderResponse(
            'message.html.twig',
            array(
                'message' => 'Bookmark removed.',
            )
        );
    }

    /**
     * @return SitePin\Model\Bookmark
     */
    protected function getNewBookmark()
    {
        return new Bookmark();
    }

    /**
     * Get a single Bookmark by Id
     *
     * @param string $id
     *
     * @return SitePin\Model\Bookmark
     */
    protected function getBookmarkById($id)
    {
        return $this->getBookmarkRepository()
            ->findOneBy(
                array(
                    'id' => $id,
                )
            )
        ;
    }

    /**
     * @return Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('entity_manager');
    }

    /**
     * @return Doctrine\ORM\EntityRepository
     */
    protected function getBookmarkRepository()
    {
        return $this->getEntityManager()
            ->getRepository('SitePin\Model\Bookmark')
        ;
    }
}
