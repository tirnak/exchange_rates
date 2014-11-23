<?php

namespace Application\Model;

class RatesDBHandler extends RatesHandler {

    protected $rates;

    public function getRates()
    {

    }

    protected function obtainRates()
    {
        $dates = date('d.m.Y');
        $cbr = file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . date('d.m.Y'));

        $xml = new \SimpleXMLElement($cbr);

        $objectManager = $this
            ->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');

        foreach($xml->children() as $node) {
            echo '(\'' . $node->CharCode . '\', \'' . $node->Nominal . '\', \'' . $node->Name . "', 0),\n";
            $currency = new \Application\Entity\Currency();
            $currency->setAbbreviation($node->CharCode);
            $currency->setNameRu($node->Name);
            $currency->setVisible(false);

            $objectManager->persist($currency);
        }

        //$objectManager->flush();
    }
} 