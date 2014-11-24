<?php

namespace Application\Model;

class RatesHandler
{

    /**
     * @var string
     * defaults to date('Y-m-d');
     */
    private $_now;
    /**
     * @var array of db results
     */
    private $_rates = array();
    private $_entityManager;

    public function __construct($entityManager, $timestamp = false)
    {
        $this->_entityManager = $entityManager;
        $this->_now = date(
            'Y-m-d',
            $timestamp ? : time()
        );
    }

    public function getRates()
    {
        if (!count($this->_rates)) {
            $this->getRatesFromMemchache();
        }

        if (!count($this->_rates)) {
            $this->getRatesFromDB();
        }

        if (!count($this->_rates)) {
            $this->getRatesFromCB();
        }
        return $this->_rates;
    }

    private function getRatesFromMemchache()
    {
        $this->_rates = MemcacheHandler::getInstance()->getRates();
    }

    private function getRatesFromDB()
    {
        $conn = $this->_entityManager->getConnection();
        $resultSet = $conn->fetchAll(
            'SELECT * FROM currency.rate r NATURAL JOIN currency.currency WHERE r.date = '
            . $conn->quote($this->_now)
        );

        $this->_rates = $resultSet;

        if (!count($resultSet)) {
            return;
        }

        MemcacheHandler::getInstance()->saveRates($this->_rates);
    }

    private function getRatesFromCB()
    {
        $cbr = file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . date('d.m.Y'));

        $xml = new \SimpleXMLElement($cbr);

        $count = 0;

        foreach ($xml->children() as $node) {

            $sql = 'INSERT INTO currency.rate(currency_id,value,date) VALUE (
                (
                    SELECT currency_id FROM currency WHERE abbreviation = ?
                ),?,?
            )';
            $conn = $this->_entityManager->getConnection();
            $count += $conn->executeUpdate(
                $sql,
                array(
                    (string)$node->CharCode,
                    (float)str_replace(',', '.', $node->Value),
                    $this->_now
                )
            );

        }

        $this->getRatesFromDB();
    }

} 