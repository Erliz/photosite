<?php

namespace Erliz\PhotoSite\Entity;


use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JsonSerializable;

/**
 * Photo
 *
 * @ORM\Table(name="photo")
 * @ORM\Entity
 */
class Photo implements JsonSerializable
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
     * @var integer
     *
     * @ORM\Column(name="weight", type="smallint", nullable=true)
     */
    private $weight;

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
     * @ORM\Column(name="is_vertical", type="smallint", nullable=false)
     */
    private $isVertical;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_available", type="smallint", nullable=false)
     */
    private $isAvailable;

    /**
     * @var Album
     *
     * @ORM\ManyToOne(targetEntity="Album", inversedBy="photos")
     * @ORM\JoinColumn(name="album", referencedColumnName="id", nullable=false)
     */
    private $album;

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * @param DateTime $createdAt
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
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set isAvailable
     *
     * @param bool $isAvailable
     *
     * @return Photo
     */
    public function setAvailable($isAvailable)
    {
        $this->isAvailable = (int) $isAvailable;

        return $this;
    }

    /**
     * Get isAvailable
     *
     * @return bool
     */
    public function isAvailable()
    {
        return (bool) $this->isAvailable;
    }

    /**
     * Set isVertical
     *
     * @param bool $isVertical
     *
     * @return Photo
     */
    public function setVertical($isVertical)
    {
        $this->isVertical = (int) $isVertical;

        return $this;
    }

    /**
     * Get isVertical
     *
     * @return integer
     */
    public function isVertical()
    {
        return (bool) $this->isVertical;
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

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     *
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray()
    {
        return array(
            'id'           => $this->getId(),
            'title'        => $this->getTitle(),
            'description'  => $this->getDescription(),
            'weight'       => $this->getWeight(),
            'is_vertical'  => $this->getIsVertical(),
            'is_available' => $this->getIsAvailable(),
            'created_at'   => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'album'        => $this->getAlbum()->toArray()
        );
    }
}
