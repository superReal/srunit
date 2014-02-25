<?php

namespace SrUnit\Mock;

use Mockery;
use SrUnit\Bootstrap;

/**
 * Class Factory
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Agentur für Neue Kommunikation
 * @package SrUnit\Mock
 * @author Jens Wiese <j.wiese AT superreal.de>
 * @author Thomas Oppelt <t.oppelt AT superreal.de>
 */
class Factory extends Mockery
{
    /**
     * Holds extended oxutilsobject factory
     *
     * @var null|\SrOxUtilsObject
     */
    protected static $sroxutilsobject = null;

    /**
     * Mocks _parent-class of used for multiinheritance in Oxid
     *
     * @param string $className
     * @return Mockery\MockInterface
     */
    public static function mockOxidParentClass($className)
    {
        Bootstrap::create()->bootstrap();

        $parentClassName = $className . '_parent';
        $mock = self::mock('overload:' . $parentClassName);

        return $mock;
    }

    /**
     * @param $className
     * @return \Mockery\Mock
     */
    public static function mockOxidAware($className)
    {
        if (null === self::$sroxutilsobject) {
            // Get real and extended oxutilsobject instance
            self::$sroxutilsobject = \oxNew('GetSrOxUtilsObject');
        }
        // Get the chained class
        $chainedClassName = self::$sroxutilsobject->getClassName(strtolower($className));
        // Mock chained class partial
        $mock = self::mock($chainedClassName)->shouldDeferMissing();
        // Set mock into registry used by sroxutilsobject class, use original classname as key
        Registry::set($className, $mock);

        return $mock;
    }
}