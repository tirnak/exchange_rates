<?php

namespace Application\Model;


class CurrencyHandler {

    private $entityManager;

    public function __construct($entityManager, $timestamp = false)
    {
        $this->entityManager = $entityManager;
    }

    public function hideValue($currency_id)
    {
        $currency = $this->entityManager->getRepository('\Application\Entity\Currency')->find($currency_id);
        $currency->setVisible(false);
        $this->entityManager->flush();
    }

    public function showValue($currency_id)
    {
        $currency = $this->entityManager->getRepository('\Application\Entity\Currency')->find($currency_id);
        $currency->setVisible(true);
        $this->entityManager->flush();
    }
} 