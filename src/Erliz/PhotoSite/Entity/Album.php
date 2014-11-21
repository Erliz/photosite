<?php

namespace Erliz\PhotoSite\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Album
 *
 * @ORM\Table(name="album")
 * @ORM\Entity
 */
class Album implements JsonSerializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
     * @var Photo
     *
     * @ORM\ManyToOne(targetEntity="Photo")
     * @ORM\JoinColumn(name="cover", referencedColumnName="id", nullable=true)
     */
    private $cover;

    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="smallint", nullable=true)
     */
    private $weight;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_available", type="smallint", nullable=false)
     */
    private $isAvailable;

    /**
     * @var Photo[]
     *
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="album")
     */
    private $photos;

    function __construct()
    {
        $this->photos = new ArrayCollection();
    }

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
     * @return Album
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
     * @return Album
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
     * @return Album
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
     * Add photos
     *
     * @param Photo $photos
     *
     * @return Album
     */
    public function addPhoto(Photo $photos)
    {
        $this->photos[] = $photos;

        return $this;
    }

    /**
     * Remove photos
     *
     * @param Photo $photos
     */
    public function removePhoto(Photo $photos)
    {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return Collection
     */
    public function getPhotos()
    {
        return $this->photos;
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

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return (bool) $this->isAvailable;
    }

    /**
     * @param int $isAvailable
     *
     * @return $this
     */
    public function setAvailable($isAvailable)
    {
        $this->isAvailable = (int) $isAvailable;

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
            'is_available' => $this->getIsAvailable(),
            'created_at'   => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'photos'       => $this->getPhotos()->toArray()
        );
    }

    /**
     * @return Photo
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @param Photo $cover
     *
     * @return $this
     */
    public function setCover(Photo $cover)
    {
        $this->cover = $cover;

        return $this;
    }
}
