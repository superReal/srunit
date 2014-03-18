<?php

/**
 * Class SrOxUtilsObject
 *
 * This Software is the property of superReal and is protected
 * by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * @link      http://www.superreal.de
 * @package sroxutilsobject\core
 * @copyright (C) superReal 2014
 * @author    Thomas Oppelt <t.oppelt _AT_ superreal.de>
 */
class SrOxUtilsObject extends SrOxUtilsObject_parent
{

    /**
     * Get instance
     *
     * @internal Overriden to bypass instance caching
     * @param $sClassName
     * @return mixed|\Mockery\Mock
     * @throws oxSystemComponentException
     */
    public function oxNew($sClassName)
    {
        $aArgs = func_get_args();
        if (defined('SRUNIT_TESTS')) {
            // Return self if required
            if ($sClassName === 'GetSrOxUtilsObject') {
                return $this;
            }
            array_shift($aArgs);
            $sClassName = strtolower($sClassName);
            $oActionObject = $this->_getMockObject($sClassName, $aArgs);
        } else {
            // Preserve default behaviour
            $oActionObject = call_user_func_array(array('parent', 'oxNew'), $aArgs);
        }

        return $oActionObject;
    }

    /**
     * Get mock object from registry
     *
     * @param $className
     * @param $aArgs
     * @return mixed
     * @throws SrUnit\Mock\Exception
     */
    protected function _getMockObject($className, $aArgs)
    {
        $mock = \SrUnit\Mock\Registry::getInstance()->get($className);
        if ($mock instanceof \Mockery\MockInterface) {
            return $mock;
        } else {
            throw new \SrUnit\Mock\Exception('Class does not implement \Mockery\MockInterface');
        }
    }

    /**
     * Manipulate class chain
     *
     * @param array $aClassChain
     * @return array
     */
    protected function _getActiveModuleChain($aClassChain)
    {
        $aClassChain = parent::_getActiveModuleChain($aClassChain);
        $aClassChain = array_filter(
            $aClassChain,
            function ($item) use (&$aClassChain) {
                // whatever
                return true;
            }
        );

        return $aClassChain;
    }
} 