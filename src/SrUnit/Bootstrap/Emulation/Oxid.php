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
        include __DIR__ . '/functions.php';

        if (false === class_exists('\oxDb')) {
            class_alias('\SrUnit\Bootstrap\Emulator\oxDb', '\oxDb');
        }
    }

}
