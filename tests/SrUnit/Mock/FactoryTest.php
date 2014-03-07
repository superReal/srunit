<?php

namespace SrUnit\Mock;

use PHPUnit_Framework_TestCase;
use Mockery;

/**
 * Class FactoryTest
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package SrUnit\Mock
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class FactoryTest extends PHPUnit_Framework_TestCase
{
    /** @var Mockery\MockInterface | MockeryProxy  */
    protected $mockeryProxy;

    /** @var Mockery\MockInterface | Registry */
    protected $registry;

    /** @var Mockery\MockInterface | \SrOxUtilsObject */
    protected $oxUtilsObject;

    protected function setUp()
    {
        $this->mockeryProxy = Mockery::mock('\SrUnit\Mock\MockeryProxy');
        $this->registry = Mockery::mock('\SrUnit\Mock\Registry');
        $this->oxUtilsObject = Mockery::mock('\SrOxUtilsObject');
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    public function testCommonMock()
    {
        $this->mockeryProxy->shouldReceive('getMock')->with('TestClass')->once();

        Factory::create('TestClass')
            ->setMockeryProxy($this->mockeryProxy)
            ->getMock();
    }

    /**
     * @expectedException \SrUnit\Mock\Exception
     * @expectedExceptionMessage Interface 'InterfaceName' does not exist.
     */
    public function testMockingNotExistingInterfaces()
    {
        Factory::create('TestClass')
            ->implementsInterface('InterfaceName')
            ->getMock();
    }

    public function testMockThatImplementsInterfaces()
    {
        $this->mockeryProxy->shouldReceive('getMock')->with('TestClass, \Iterator, \ArrayAccess')->once();

        Factory::create('TestClass')
            ->setMockeryProxy($this->mockeryProxy)
            ->implementsInterface('\Iterator')
            ->implementsInterface('\ArrayAccess')
            ->getMock();
    }

    public function testMockThatShouldBeRegisteredForOxidFactory()
    {
        $dummyMock = Mockery::mock('Dummy');

        $this->mockeryProxy->shouldReceive('getMock')->andReturn($dummyMock);
        $this->registry->shouldReceive('set')->with('TestClass', $dummyMock)->once();
        $this->oxUtilsObject->shouldReceive('getClassName')->with('testclass')->once();

        Factory::create('TestClass')
            ->setMockeryProxy($this->mockeryProxy)
            ->setRegistry($this->registry)
            ->setOxUtilsObject($this->oxUtilsObject)
            ->registerForOxidFactory()
            ->getMock();
    }

    public function testMockingExistingObject()
    {
        $this->markTestIncomplete();
    }
}
 