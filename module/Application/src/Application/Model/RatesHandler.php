<?php

namespace Application\Model;

abstract class RatesHandler {

    protected $rates;
    protected $currency;
    protected $entityManager;
    abstract public function getRates();
    abstract protected function obtainRates();
} 