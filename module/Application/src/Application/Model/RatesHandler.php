<?php

namespace Application\Model;

class RatesHandler {

    /**
     * @var string
     * defaults to date('Y-m-d');
     */
    private $now;
    /**
     * @var array of db results
     */
    private $rates = array();
    private $entityManager;

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
        $this->rates = MemcacheHandler::getInstance()->getRates();
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

        MemcacheHandler::getInstance()->saveRates($this->rates);
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

} 