<?php

namespace SrUnit\Bootstrap\Emulator;

/**
 * Class OXIDEmulation
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package SrUnit\Mock
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class Oxid
{
    public static function emulate()
    {
        if (false === function_exists('oxNew')) {
            function oxNew($className) {
                return Registry::getInstance()->get(strtolower($className));
            }
        }

        if (false === class_exists('oxDb')) {
            class_alias('\SrUnit\Bootstrap\Emulator\oxDb', 'oxDb');
        }
    }


} 