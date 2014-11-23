<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Doctrine\DBAL\Event\Listeners\MysqlSessionInit;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function getRatesAction()
    {
        $jsonModel = new JsonModel(array(
            'qwerty' => 'zxcvb'
        ));

        return $jsonModel;
    }

    public function fillCurrenciesAction() {
        header('Content-Type: text/html; charset=utf-8');
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

    public function doAction() {
        ini_set("display_errors", 1);
        ini_set("track_errors", 1);
        ini_set("html_errors", 1);
        ini_set('default_charset', 'utf-8');
        error_reporting(E_ALL);

        $em = $this->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');

        $em->getEventManager()->addEventSubscriber(new MysqlSessionInit("utf8", "utf8_unicode_ci"));
        $rrh = new \Application\Model\RatesRemoteHandler($em);

        header('Content-Type: text/html; charset=UTF-8');

        return new JsonModel(
            $rrh->getRates()
        );
    }
}
