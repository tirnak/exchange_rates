<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
//use Doctrine\Common\Annotations\JoinColumn as JoinColumn;
//use Doctrine\Common\Annotations\ManyToOne as ManyToOne;

/**
 * @ORM\Entity
 * @ORM\Table(name="rate")
 */
class Rate {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="rate_id", type="integer")
     */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="Application\Entity\Currency") */
    /** @ORM\JoinColumn(name="currency_id", referencedColumnName="id") */
    protected $currency;

    /** @ORM\Column(type="date") */
    protected $date;

    /** @ORM\Column(type="float") */
    protected $value;

    /**
     * @param \Date $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @param float $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param Currency $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }


} 