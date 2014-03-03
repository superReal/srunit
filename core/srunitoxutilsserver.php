<?php

/**
 * Class SrUnitOxUtilsServer
 *
 * This Software is the property of superReal and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @link      http://www.superreal.de
 * @copyright (C) superReal 2014
 * @author    Sebastian Kueck <s.kueck _AT_ superreal.de>
 */
class SrUnitOxUtilsServer extends SrUnitOxUtilsServer_parent
{
    public function setOxCookie($sName, $sValue = "", $iExpire = 0, $sPath = '/', $sDomain = null, $blToSession = true, $blSecure = false)
    {
        if (defined('SRUNIT_OXID')) {
            return null;
        }
        return parent::setOxCookie($sName, $sValue, $iExpire, $sPath, $sDomain, $blToSession, $blSecure);
    }
}