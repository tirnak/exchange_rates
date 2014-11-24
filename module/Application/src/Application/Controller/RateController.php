<?php

namespace Application\Controller;

use Application\Model\MemcacheHandler;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class RateController extends AbstractActionController
{
    public function getAction()
    {
        $em = $this->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');

        $rh = new \Application\Model\RatesHandler($em);

        return new JsonModel(
            $rh->getRates()
        );
    }

    public function hideAction()
    {

        $em = $this->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');

        $request = json_decode(
            file_get_contents("php://input")
        );

        if (!isset($request->currency_id)) {
            exit;
        }

        $ch = new \Application\Model\CurrencyHandler($em);

        $ch->hideValue(
            $request->currency_id
        );

        MemcacheHandler::getInstance()->clearRates();
        return new JsonModel(array());
    }

    public function showAction()
    {
        $em = $this->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');

        $request = json_decode(
            file_get_contents("php://input")
        );

        if (!isset($request->currency_id)) {
            exit;
        }

        $ch = new \Application\Model\CurrencyHandler($em);

        $ch->showValue(
            $request->currency_id
        );

        MemcacheHandler::getInstance()->clearRates();
        return new JsonModel(array());
    }
} 