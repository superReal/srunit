<?php

namespace SrUnit\Tests\Mock;

use PHPUnit_Framework_TestCase;
use Mockery;
use SrUnit\Mock\Factory;

/**
 * Class FactoryTest
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package SrUnit\Tests\Mock
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

    public static function setUpBeforeClass()
    {
        date_default_timezone_set('UTC');
    }

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

    /**
     * @expectedException \SrUnit\Mock\Exception
     * @expectedExceptionMessage Could not create mock. You have to provide a class-name when using SrUnit\Mock\Factory::create.
     */
    public function testTypeCheckOnFactoryCreate()
    {
        Factory::create(1234);
    }

    /**
     * @expectedException \SrUnit\Mock\Exception
     * @expectedExceptionMessage Could not create mock. You have to provide an object when using SrUnit\Mock\Factory::createFromObject.
     */
    public function testTypeCheckOnFactoryCreateFrom()
    {
        Factory::createFromObject('className');
    }

    public function testSimpleMock()
    {
        $this->mockeryProxy->shouldReceive('getMock')->with('TestClass')->once();

        Factory::create('TestClass')
            ->setMockeryProxy($this->mockeryProxy)
            ->getMock();
    }

    public function testCreatingMockThroughActualMockeryProxy()
    {
        $mock = Factory::create('TestClass')->getMock();
        $mock->shouldReceive('getFoo')->andReturn('bar');

        $this->assertInstanceOf('Mockery\MockInterface', $mock);
        $this->assertEquals('bar', $mock->getFoo());
    }

    /**
     * @expectedException \SrUnit\Mock\Exception
     * @expectedExceptionMessage Interface 'InterfaceName' does not exist.
     */
    public function testMockingNotExistingInterface()
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

    public function testMockWhichImplementsIteratorWithData()
    {
        $data = array('foo', 'bar', 'barz');

        $dummyMock = Mockery::mock('\SrUnit\Mock\MockGenerator\CustomMockInterface');
        $dummyMock->shouldReceive('implementsIterator')->with($data)->once();
        $this->mockeryProxy->shouldReceive('getMock')->andReturn($dummyMock);

        Factory::create('TestClass')
            ->setMockeryProxy($this->mockeryProxy)
            ->implementsInterface('\Iterator', $data)
            ->getMock();
    }

    /**
     * @expectedException \SrUnit\Mock\Exception
     * @expectedExceptionMessage Could not apply data for interface '\Traversable'. Method 'implementsTraversable' does not exists on Mock.
     */
    public function testMockWhichImplementsIteratorWithDataButMissingMethod()
    {
        $data = array('foo', 'bar', 'barz');

        $dummyMock = Mockery::mock('Dummy');
        $this->mockeryProxy->shouldReceive('getMock')->andReturn($dummyMock);

        Factory::create('TestClass')
            ->setMockeryProxy($this->mockeryProxy)
            ->implementsInterface('\Traversable', $data)
            ->getMock();
    }

    public function testMockThatShouldBeRegisteredForOxidFactory()
    {
        $dummyMock = Mockery::mock('Dummy');

        $this->mockeryProxy->shouldReceive('getMock')->andReturn($dummyMock);
        $this->registry->shouldReceive('set')->with('TestClass', $dummyMock)->once();

        Factory::create('TestClass')
            ->setMockeryProxy($this->mockeryProxy)
            ->setRegistry($this->registry)
            ->registerForOXID()
            ->getMock();
    }

    public function testMockingObject()
    {
        $dummyObject = new \stdClass;

        $mock = Factory::createFromObject($dummyObject)->getMock();
        $this->assertInstanceOf('\Mockery\MockInterface', $mock);
        $this->assertInstanceOf('\stdClass', $mock);
    }

    public function testCreatingParentClass()
    {
        $mock = Factory::createParentClass('\whateverClass_parent')->getMock();

        $mock->shouldReceive('getFoo')->andReturn('bar');

        $actualObject = new \whateverClass_parent();

        $this->assertInstanceOf('\whateverClass_parent', $actualObject);
        $this->assertEquals('bar', $actualObject->getFoo());
    }

    public function testProvisioningOxidModel()
    {
        $mock = Factory::create('\oxArticle')
            ->useProvisioning()
            ->getMock();

        $this->assertInstanceOf('\oxField', $mock->oxarticles__oxid);
    }
}