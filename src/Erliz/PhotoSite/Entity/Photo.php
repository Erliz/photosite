<?php

namespace Erliz\PhotoSite\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Photo
 *
 * @ORM\Table(name="photo")
 * @ORM\Entity
 */
class Photo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_available", type="smallint", nullable=false)
     */
    private $isAvailable;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_vertical", type="smallint", nullable=false)
     */
    private $isVertical;


    /**
     * @var Album
     *
     * @ORM\ManyToOne(targetEntity="Album", inversedBy="photos")
     * @ORM\JoinColumn(name="photo_id", referencedColumnName="id", nullable=false)
     */
    private $album;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Photo
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Photo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Photo
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set isAvailable
     *
     * @param integer $isAvailable
     *
     * @return Photo
     */
    public function setIsAvailable($isAvailable)
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    /**
     * Get isAvailable
     *
     * @return integer
     */
    public function getIsAvailable()
    {
        return $this->isAvailable;
    }

    /**
     * Set isVertical
     *
     * @param integer $isVertical
     *
     * @return Photo
     */
    public function setIsVertical($isVertical)
    {
        $this->isVertical = $isVertical;

        return $this;
    }

    /**
     * Get isVertical
     *
     * @return integer
     */
    public function getIsVertical()
    {
        return $this->isVertical;
    }

    /**
     * Set album
     *
     * @param Album $album
     *
     * @return Photo
     */
    public function setAlbum(Album $album)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album
     *
     * @return Album
     */
    public function getAlbum()
    {
        return $this->album;
    }
}
