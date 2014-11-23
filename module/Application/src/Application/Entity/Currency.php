<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="currency")
 */
class Currency {
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param mixed $abbreviation
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;
    }

    /**
     * @return mixed
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * @param mixed $name_ru
     */
    public function setNameRu($name_ru)
    {
        $this->name_ru = $name_ru;
    }

    /**
     * @return mixed
     */
    public function getNameRu()
    {
        return $this->name_ru;
    }

    /**
     * @param boolean $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    /**
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="currency_id", type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $abbreviation;

    /** @ORM\Column(type="string") */
    protected $name_ru;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $visible;
}