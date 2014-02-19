<?php

namespace SrUnit\Mock;

use Mockery;

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
    public static function mockParentClass($className)
    {
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
            // Get SrOxUtilsObject instance
            self::$sroxutilsobject = \oxNew('GetSrOxUtilsObject');
        }
        $chainedClassName = self::$sroxutilsobject->getClassName(strtolower($className));
        $mock = self::mock($chainedClassName)->shouldDeferMissing();

        Registry::set($className, $mock);

        return $mock;
    }
}