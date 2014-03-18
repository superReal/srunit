<?php

namespace SrUnit\Mock;

use SrUnit\TestCase;

class RegistryTest extends TestCase
{

    public function tearDown()
    {
        Registry::getInstance()->resetAll();
    }

    public function testCreatingRegistryAlwaysReturnsSameInstance()
    {
        $registry1 = Registry::getInstance();
        $registry2 = Registry::getInstance();

        $this->assertSame($registry1, $registry2);
    }

    public function testRegisterObject()
    {
        $expectedObject = new \stdClass();
        Registry::getInstance()->set('className', $expectedObject);
        $actualObject = Registry::getInstance()->get('className');

        $this->assertSame($expectedObject, $actualObject);
    }

    /**
     * @expectedException \SrUnit\Mock\Exception
     */
    public function testRetrievingObjectThatIsNotRegisteredYet()
    {
        Registry::getInstance()->get('foobarbatz');
    }

    /**
     * @expectedException \SrUnit\Mock\Exception
     */
    public function testResettingAllInstances()
    {
        Registry::getInstance()->set('className1', new \stdClass());
        Registry::getInstance()->resetAll();
        Registry::getInstance()->get('className1');
    }

    /**
     * @depends testResettingAllInstances
     */
    public function testInstanceKeysAreReturnedCorrect()
    {
        Registry::getInstance()->set('className1', new \stdClass());
        Registry::getInstance()->set('className2', new \stdClass());
        Registry::getInstance()->set('className3', new \stdClass());

        $expectedKeys = array('classname1', 'classname2', 'classname3');
        $actualKeys = Registry::getInstance()->getKeys();

        $this->assertEquals($expectedKeys, $actualKeys);
    }
}
 