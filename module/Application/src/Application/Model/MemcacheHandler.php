<?php

namespace Application\Model;


class MemcacheHandler {

    const MEMCACHE_KEY = 'rates';
    /**
     * @var \Memcache
     */
    private $memcache;
    private static $__instance = null;

    public static function getInstance()
    {
        if (self::$__instance === null) {
            self::$__instance = new MemcacheHandler();
        }
        return self::$__instance;
    }

    private function __construct() {}

    public function getRates()
    {
        if (!$this->isMemcacheSet()) {
            return array();
        }
        if ($ratesSerialized = $this->memcache->get(self::MEMCACHE_KEY)) {
            return $ratesSerialized;
        } else {
            return array();
        }
    }

    public function saveRates($rates)
    {
        if ($this->isMemcacheSet()) {
            $restOfDayInSec = strtotime('+1 day', mktime(0, 0, 0)) - time();

            $this->memcache->add(
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
            $this->memcache->delete(
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