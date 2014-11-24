<?php

namespace Application\Model;


class MemcacheHandler
{

    const MEMCACHE_KEY = 'rates';
    /**
     * @var \Memcache
     */
    private $_memcache;
    /**
     * @var MemcacheHandler
     */
    private static $_instance = null;

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new MemcacheHandler();
        }
        return self::$_instance;
    }

    private function __construct()
    {

    }

    public function getRates()
    {
        if (!$this->isMemcacheSet()) {
            return array();
        }
        if ($ratesSerialized = $this->_memcache->get(self::MEMCACHE_KEY)) {
            return $ratesSerialized;
        } else {
            return array();
        }
    }

    public function saveRates($rates)
    {
        if ($this->isMemcacheSet()) {
            $restOfDayInSec = strtotime('+1 day', mktime(0, 0, 0)) - time();

            $this->_memcache->add(
                self::MEMCACHE_KEY,
                $rates,
                false,
                $restOfDayInSec
            );
        }
    }

    public function clearRates()
    {
        if ($this->isMemcacheSet()) {
            $this->_memcache->delete(
                self::MEMCACHE_KEY
            );
        }
    }

    /**
     * @return bool
     */
    private function isMemcacheSet()
    {
        if (!class_exists('Memcache')) {
            return false;
        }
        if (!is_a($this->_memcache, 'Memcache')) {
            $this->_memcache = new \Memcache();
            if (!$this->_memcache->connect('127.0.0.1', 11211)) {
                return false;
            } else {
                return true;
            }
        }
        return true;
    }
} 