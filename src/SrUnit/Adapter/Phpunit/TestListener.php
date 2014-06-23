<?php

namespace SrUnit\Adapter\Phpunit;

use Mockery\Adapter\Phpunit\PHPUnit_Framework_Test;

/**
 * Class TestListener
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package SrUnit\Adapter\Phpunit
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class TestListener extends \Mockery\Adapter\Phpunit\TestListener
{
    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        if ($this->suiteNeedsOXID($suite)) {
            $this->loadOXID();
            $this->activateModule();
        }

        $suite->setRunTestInSeparateProcess(true);
        $suite->setInIsolation(true);

        parent::startTestSuite($suite);
    }

    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        $this->deactivateModule();

        parent::endTestSuite($suite);
    }

    /**
     * @param \PHPUnit_Framework_TestSuite $suite
     * @return bool
     */
    protected function suiteNeedsOXID(\PHPUnit_Framework_TestSuite $suite)
    {
        $needsOXID = in_array('needs-oxid', $suite->getGroups());

        return $needsOXID;
    }

    /**
     * 
     */
    protected function loadOXID()
    {
        require_once __DIR__ . '/../../../../../../../bootstrap.php';
    }

    /**
     * Activate sR Unit OXID module
     *
     * @throws \InvalidArgumentException
     */
    protected function activateModule()
    {
        if (false === class_exists('\oxModule')) {
            throw new \InvalidArgumentException('Could not load sR Unit Module, because OXID is not available.');
        }

        $module = new \oxModule();
        $module->load('srunit');

        if (false === $module->isActive()) {
            $module->activate();
        }

        define('SRUNIT_TESTS', true);
    }

    /**
     * Deactivate sR Unit OXID module
     */
    protected function deactivateModule()
    {
        $module = new \oxModule();
        $module->load('srunit');
        if ($module->isActive()) {
            $module->deactivate();
        }
    }
}
