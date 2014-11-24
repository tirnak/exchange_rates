<?php

namespace Application\Model;


class CurrencyHandler
{

    private $_entityManager;

    public function __construct($entityManager, $timestamp = false)
    {
        $this->_entityManager = $entityManager;
    }

    public function hideValue($currency_id)
    {
        $currency = $this
            ->_entityManager
            ->getRepository('\Application\Entity\Currency')
            ->find($currency_id);
        $currency->setVisible(false);
        $this->_entityManager->flush();
    }

    public function showValue($currency_id)
    {
        $currency = $this
            ->_entityManager
            ->getRepository('\Application\Entity\Currency')
            ->find($currency_id);
        $currency->setVisible(true);
        $this->_entityManager->flush();
    }
} 