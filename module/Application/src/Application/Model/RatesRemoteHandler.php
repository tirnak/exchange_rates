<?php

namespace Application\Model;

class RatesRemoteHandler {

    /**
     * @var string
     * defaults to date('Y-m-d');
     */
    private $now;
    /**
     * @var array of db results
     */
    private $rates = array();
    /**
     * @var \Memcache
     */
    private $memcache;
    private $memcache_key = 'rates';

    public function __construct($entityManager, $timestamp = false)
    {
        $this->entityManager = $entityManager;
        $this->now = date('Y-m-d',
            $timestamp ? : time()
        );
    }

    public function getRates()
    {
        if (!count($this->rates)) {
            $this->getRatesFromMemchache();
        }

        if (!count($this->rates)) {
            $this->getRatesFromDB();
        }

        if (!count($this->rates)) {
            $this->getRatesFromCB();
        }
        return $this->rates;
    }

    private function getRatesFromMemchache()
    {
        if (!$this->isMemcacheSet()) {
            return false;
        }
        if ($ratesSerialized = $this->memcache->get($this->memcache_key)) {
            $this->rates = $ratesSerialized;
            return true;
        } else {
            return false;
        }
    }

    private function getRatesFromDB()
    {
        $conn = $this->entityManager->getConnection();
        $resultSet = $conn->fetchAll(
            'SELECT * FROM currency.rate r NATURAL JOIN currency.currency WHERE r.date = '
            . $conn->quote($this->now)
        );

        $this->rates = $resultSet;

        if (!count($resultSet)) {
            return;
        }

        if ($this->isMemcacheSet()) {
            $restOfDayInSec = strtotime('+1 day', mktime(0, 0, 0)) - time();

            $this->memcache->add(
                $this->memcache_key,
                $resultSet,
                false,
                $restOfDayInSec
            );
        }
    }

    private function getRatesFromCB()
    {
        $cbr = file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . date('d.m.Y'));

        $xml = new \SimpleXMLElement($cbr);

        $count = 0;

        foreach($xml->children() as $node) {

            $sql = 'INSERT INTO currency.rate(currency_id,value,date) VALUE (
                (
                    SELECT currency_id FROM currency WHERE abbreviation = ?
                ),?,?
            )';
            $conn = $this->entityManager->getConnection();
            $count += $conn->executeUpdate($sql,
                array(
                    (string)$node->CharCode,
                    (float)str_replace(',' ,'.', $node->Value),
                    $this->now
                )
            );

        }

        $this->getRatesFromDB();
    }

    /**
     * @return bool
     */
    private function isMemcacheSet()
    {
        if (!class_exists('Memcache')) {
            return false;
        }
        if (!is_a($this->memcache, 'Memcache')) {
            $this->memcache = new \Memcache();
            if (!$this->memcache->connect('127.0.0.1', 11211)) {
                return false;
            }
            else {
                return true;
            }
        }
        return true;
    }
} 