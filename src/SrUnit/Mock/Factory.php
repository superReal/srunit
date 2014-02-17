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
 */
class Factory extends Mockery
{
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
}