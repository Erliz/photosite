<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 21.11.2014
 */

namespace Erliz\PhotoSite\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Setting
 *
 * @ORM\Table(name="setting")
 * @ORM\Entity
 */
class Setting
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=false)
     * @ORM\Id
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=128, nullable=false)
     */
    private $value;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
} 