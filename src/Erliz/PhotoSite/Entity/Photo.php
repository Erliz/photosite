<?php

namespace Erliz\PhotoSite\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
}
