<?php
/**
 * Class oxRegistry
 *
 * This Software is the property of superReal and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @link      http://www.superreal.de
 * @package   SrUnit\Bootstrap\Emulation
 * @copyright (C) superReal 2014
 * @author    Sebastian Kueck <s.kueck _AT_ superreal.de>
 */

namespace SrUnit\Bootstrap\Emulation;

use SrUnit\Mock\Factory;
use SrUnit\Mock\Registry;

class oxRegistry
{
    public static function get($className)
    {
        return Registry::getInstance()->get(strtolower($className));
    }

    public static function getConfig()
    {
        return Registry::getInstance()->get(strtolower('oxConfig'));
    }

    public static function getLang()
    {
        return Registry::getInstance()->get(strtolower('oxLang'));
    }

    public static function getSession()
    {
        return Registry::getInstance()->get(strtolower('oxSession'));
    }

    public static function getUtils()
    {
        return Registry::getInstance()->get(strtolower('oxUtils'));
    }

} 
