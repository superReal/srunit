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
        \SrUnit\Mock\Factory::mockOxidAware('oxmodulelist');
        \SrUnit\Mock\Factory::mockOxidAware('oxconfig');
        \SrUnit\Mock\Factory::mockOxidAware('oxutilsfile');
        \SrUnit\Mock\Factory::mockOxidAware('oxsession');
        \SrUnit\Mock\Factory::mockOxidAware('oxserial');
        \SrUnit\Mock\Factory::mockOxidAware('oxutilsserver')->shouldReceive('setOxCookie')->andReturnNull();
        \SrUnit\Mock\Factory::mockOxidAware('oxutils');
        \SrUnit\Mock\Factory::mockOxidAware('oxutilsdate');
        \SrUnit\Mock\Factory::mockOxidAware('oxlang');
        \SrUnit\Mock\Factory::mockOxidAware('oxlist');
        \SrUnit\Mock\Factory::mockOxidAware('oxarticlelist');
        \SrUnit\Mock\Factory::mockOxidAware('oxarticle');
        \SrUnit\Mock\Factory::mockOxidAware('oxfield');
    }


    /**
     * Test getArticlesFiles()
     */
    public function testGetArticleFiles()
    {
        $mock = \SrUnit\Mock\Registry::getInstance()->get('oxArticle');

        $actualValue = $mock->getArticleFiles()->offsetGet(0);
        $expectedValue = 'faked file';

        $this->assertEquals($expectedValue, $actualValue);

    }
}


