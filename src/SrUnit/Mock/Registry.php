<?php
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

namespace SrUnit\Mock;


class Registry
{

    /**
     * Instance array
     *
     * @var array
     */
    protected static $instances = array();


    /**
     * Instance getter
     *
     * @param $className
     * @return \Mockery\Mock
     * @throws Exception
     */
    public static function get($className)
    {
        $className = strtolower($className);
        if (isset(self::$instances[$className])) {
            return self::$instances[$className];
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
            unset(self::$instances[$className]);
            return;
        }

        self::$instances[$className] = $instance;
    }

    /**
     * Get instance keys
     *
     * @return array
     */
    public static function getKeys()
    {
        return array_keys(self::$instances);
    }

}