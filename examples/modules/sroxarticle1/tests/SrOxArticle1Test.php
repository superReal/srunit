<?php

use SrUnit\TestCase;

/**
 * Class SrOxOrderTest
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH 2014
 * @author Thomas Oppelt <t.oppelt AT superreal.de>
 */
class SrOxArticle1Test extends TestCase
{

    /**
     * SetUp Testcase
     */
    public function setUp()
    {
        \SrUnit\Mock\Factory::mockOxidAware('oxmodulelist')->shouldDeferMissing();
        \SrUnit\Mock\Factory::mockOxidAware('oxconfig')->shouldDeferMissing();
        \SrUnit\Mock\Factory::mockOxidAware('oxutilsfile')->shouldDeferMissing();
        \SrUnit\Mock\Factory::mockOxidAware('oxsession')->shouldDeferMissing();
        \SrUnit\Mock\Factory::mockOxidAware('oxserial')->shouldDeferMissing();
        \SrUnit\Mock\Factory::mockOxidAware('oxutilsserver')->shouldDeferMissing();
        \SrUnit\Mock\Factory::mockOxidAware('oxutils')->shouldDeferMissing();
        \SrUnit\Mock\Factory::mockOxidAware('oxutilsdate')->shouldDeferMissing();
        \SrUnit\Mock\Factory::mockOxidAware('oxlist')->shouldDeferMissing();
    }


    /**
     * Test getArticlesFiles()
     */
    public function testGetArticleFiles()
    {
        $mock = \SrUnit\Mock\Factory::mockOxidAware('oxArticle');

        $actualValue = $mock->getArticleFiles()->offsetGet(0);
        $expectedValue = 'faked file';

        $this->assertEquals($expectedValue, $actualValue);
    }
}


