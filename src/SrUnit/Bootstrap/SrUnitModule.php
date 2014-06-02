<?php

namespace SrUnit\Bootstrap;

/**
 * Class ModuleLoader
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package SrUnit\Bootstrap
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class SrUnitModule
{
    /**
     * Activate SrUnit module
     */
    public static function activate()
    {
        define('SRUNIT_TESTS', true);

        $module = new \oxModule();
        $module->load('srunit');
        if (false === $module->isActive()) {
            $module->activate();
        }
    }

    /**
     * Deactivate SrUnit module
     */
    public static function deactivate()
    {
        $module = new \oxModule();
        $module->load('srunit');
        if (true === $module->isActive()) {
            $module->deactivate();
        }
    }
} 