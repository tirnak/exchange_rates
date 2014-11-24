<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function fillCurrenciesAction() {
        $cbr = file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . date('d.m.Y'));

        $xml = new \SimpleXMLElement($cbr);

        $objectManager = $this
            ->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');

        foreach($xml->children() as $node) {
            $currency = new \Application\Entity\Currency();
            $currency->setAbbreviation($node->CharCode);
            $currency->setNameRu($node->Name);
            $currency->setVisible(false);

            $objectManager->persist($currency);
        }

        $objectManager->flush();

    }

}
