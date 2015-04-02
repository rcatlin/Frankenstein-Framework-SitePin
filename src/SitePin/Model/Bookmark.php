<?php

namespace SitePin\Model;

/**
 * @Entity
 * @Table(name="bookmarks")
 */
class Bookmark
{
    /**
     * @var integer
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @var string
     *
     * @Column(type="string")
     */
    protected $uri;

    /**
     * @var name;
     *
     * @Column(type="string")
     */
    protected $name;

    public function getId()
    {
        return $this->id;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
}
