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

} 