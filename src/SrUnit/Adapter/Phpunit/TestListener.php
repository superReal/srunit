<?php

namespace SrUnit\Adapter\Phpunit;

use SrUnit\Mock\Registry;

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
    /**
     * {@inheritdoc}
     */
    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        $this->markTestsThatNeedOXID($suite);
        parent::startTestSuite($suite);
    }

    /**
     * {@inheritdoc}
     */
    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        parent::endTestSuite($suite);
    }

    /**
     * {@inheritdoc}
     */
    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        Registry::getInstance()->resetAll();
        parent::endTest($test, $time);
    }

    /**
     * Marks tests within suite that needs OXID (by annotation)
     *
     * @param \PHPUnit_Framework_TestSuite $suite
     * @throws \RuntimeException
     */
    protected function markTestsThatNeedOXID(\PHPUnit_Framework_TestSuite $suite)
    {
        if (false === in_array('needs-oxid', $suite->getGroups())) {
            return;
        }

        $groupedTests = $suite->getGroupDetails();

        foreach ($groupedTests['needs-oxid'] as $test) {
            if ($test instanceof \PHPUnit_Framework_TestSuite) {
                continue;
            }

            if (false === method_exists($test, 'setNeedsOXID')) {
                throw new \RuntimeException(
                    sprintf(
                        'Test "%s" must extend "SrUnit\TestCase" - is derived from "%s".',
                        get_class($test),
                        get_parent_class($test)
                    )
                );
            }

            $test->setNeedsOXID(true);
        }
    }
}
