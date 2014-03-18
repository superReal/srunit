<?php

namespace SrUnit\Mock;

/**
 * Class Registry
 *
 * This Software is the property of superReal and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @link      http://www.superreal.de
 * @package SrUnit\Mock
 * @copyright (C) superReal 2014
 * @author    Thomas Oppelt <t.oppelt _AT_ superreal.de>
 */
class Registry
{

    /** @var Registry */
    protected static $instance;

    /**
     * Instance array
     *
     * @var array
     */
    protected $registeredInstances = array();

    /**
     * @return Registry
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new Registry();
        }

        return static::$instance;
    }

    /**
     * Instance getter
     *
     * @param $className
     * @return \Mockery\Mock
     * @throws Exception
     */
    public function get($className)
    {
        $className = strtolower($className);
        if (isset($this->registeredInstances[$className])) {
            return $this->registeredInstances[$className];
        } else {
            throw new Exception("Mock instance missing for {$className}");
        }
    }

    /**
     * Instance setter
     *
     * @param string $className Class name
     * @param object $instance Object instance
     *
     * @static
     *
     * @return null
     */
    public function set($className, $instance)
    {
        $className = strtolower($className);

        if (is_null($instance)) {
            unset($this->registeredInstances[$className]);
            return;
        }

        $this->registeredInstances[$className] = $instance;
    }

    /**
     * Reset all registered instances
     */
    public function resetAll()
    {
        $this->registeredInstances = array();
    }

    /**
     * Get instance keys
     *
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->registeredInstances);
    }
}