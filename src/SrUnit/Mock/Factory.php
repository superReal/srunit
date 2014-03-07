<?php

namespace SrUnit\Mock;

use Mockery;
use SrUnit\Mock\Builder\AbstractProvisioner;
use SrUnit\Mock\MockGenerator\CustomMockInterface;
use OutOfBoundsException;
use SrOxUtilsObject;

/**
 * Class Factory
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package SrUnit\Mock
 * @author Jens Wiese <j.wiese AT superreal.de>
 * @author Thomas Oppelt <t.oppelt AT superreal.de>
 */
class Factory
{
    /**
     * @var MockeryProxy
     */
    protected $mockeryProxy;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * Holds class-name of class to mock
     *
     * @var string Class-name of mock
     */
    protected $originalClassName;

    /**
     * Holds class-name of mock
     *
     * @var string Class-name of mock
     */
    protected $mockClassName;

    /**
     * Holds created mock-object
     *
     * @var Mockery\MockInterface | CustomMockInterface
     */
    protected $mockObject;

    /**
     * @var array
     */
    protected $mockInterfaces = array();

    /**
     * @var array
     */
    protected $mockInterfaceData = array();

    /**
     * @var bool
     */
    protected $shouldBeRegisteredForOxidFactory = false;

    /**
     * @var bool
     */
    protected $shouldBeProvisioned = false;

    /**
     * Holds extended oxutilsobject factory
     *
     * @var SrOxUtilsObject
     */
    protected $oxUtilsObject;

    /**
     * @var AbstractProvisioner
     */
    protected $provisioner;

    /**
     * @param string $className
     * @return Factory
     */
    public static function create($className)
    {
        return new self($className);
    }

    /**
     * @param string $className
     */
    public function __construct($className)
    {
        $this->originalClassName = $className;
    }

    /**
     * @param MockeryProxy $proxy
     * @return $this
     */
    public function setMockeryProxy(MockeryProxy $proxy)
    {
        $this->mockeryProxy = $proxy;

        return $this;
    }

    /**
     * @param \SrUnit\Mock\Registry $registry
     * @return $this
     */
    public function setRegistry($registry)
    {
        $this->registry = $registry;

        return $this;
    }

    /**
     * @param \SrOxUtilsObject $oxUtilsObject
     * @return $this
     */
    public function setOxUtilsObject($oxUtilsObject)
    {
        $this->oxUtilsObject = $oxUtilsObject;

        return $this;
    }

    /**
     * Returns mock-object
     *
     * @return Mockery\MockInterface|CustomMockInterface
     */
    public function getMock()
    {
        $this->mockObject = $this->getMockeryProxy()->getMock($this->getMockTargetsAsString());

        if ($this->shouldBeRegisteredForOxidFactory) {
            $this->mockObject->shouldDeferMissing();
            $this->getRegistry()->set($this->originalClassName, $this->mockObject);
        }

        if ($this->shouldBeProvisioned) {
            $this->getProvisioner()->apply($this->mockObject);
        }

        $this->applyDataForInterfaces();

        return $this->mockObject;
    }

    /**
     * Enables mocking of _parent class for OXID multi-inheritance
     *
     * @return $this
     */
    public function extendsOxidParentClass()
    {
        $this->mockClassName = 'overload:' . $this->originalClassName . '_parent';

        return $this;
    }

    /**
     * Adds given interface to list of implemented interfaces
     *
     * @param string $interfaceName
     * @param mixed $data
     * @throws Exception
     * @return $this
     */
    public function implementsInterface($interfaceName, $data = null)
    {
        if (false === interface_exists($interfaceName)) {
            throw new Exception("Interface '{$interfaceName}' does not exist.");
        }

        array_push($this->mockInterfaces, $interfaceName);
        $this->mockInterfaceData[$interfaceName] = $data;

        return $this;
    }

    /**
     * Enables provisioning of mock-object
     */
    public function useProvisioning()
    {
        $this->shouldBeProvisioned = true;
    }

    /**
     * Enables registration of mock object for OXIDs oxNew()
     *
     * @return $this
     */
    public function registerForOxidFactory()
    {
        $this->mockClassName = $this->getOxUtilsObject()->getClassName(strtolower($this->originalClassName));
        $this->shouldBeRegisteredForOxidFactory = true;

        return $this;
    }

    /**
     * @return bool
     */
    protected function shouldImplementInterfaces()
    {
        return !empty($this->mockInterfaces);
    }

    /**
     * Returns provisioner for specific class-name
     *
     * @throws OutOfBoundsException
     * @return AbstractProvisioner
     */
    protected function getProvisioner()
    {
        if (is_null($this->provisioner)) {
            $provisionerClassName = __NAMESPACE__ . "\\Provisioner" . $this->originalClassName . 'Provisioner';

            if (false === class_exists($provisionerClassName)) {
                throw new OutOfBoundsException(
                    sprintf('Could not load provisioner "%s".', $provisionerClassName)
                );
            }

            $this->provisioner = new $provisionerClassName;
        }

        return $this->provisioner;
    }

    /**
     * @return MockeryProxy
     */
    protected function getMockeryProxy()
    {
        if (is_null($this->mockeryProxy)) {
            $this->mockeryProxy = new MockeryProxy();
        }

        return $this->mockeryProxy;
    }

    /**
     * @return \SrUnit\Mock\Registry
     */
    protected function getRegistry()
    {
        if (is_null($this->registry)) {
            $this->registry = new Registry();
        }

        return $this->registry;
    }

    /**
     * @return \SrOxUtilsObject
     */
    protected function getOxUtilsObject()
    {
        if (is_null($this->oxUtilsObject)) {
            $this->oxUtilsObject = \oxNew('GetSrOxUtilsObject');
        }

        return $this->oxUtilsObject;
    }

    /**
     * Apply data for defined interfaces (e.g. Iterator, ArrayAccess)
     */
    protected function applyDataForInterfaces()
    {
        foreach ($this->mockInterfaces as $interfaceName) {
            $methodName = 'implements' . $interfaceName;
            $hasMethod = method_exists($this->mockObject, $methodName);
            $hasData = isset($this->mockInterfaceData[$interfaceName]);

            if ($hasData && false === $hasMethod) {
                throw new Exception(
                    sprintf(
                        "Could not apply data for interface '%s'. Method '%s' does not exists on Mock.",
                        $interfaceName,
                        $methodName
                    )
                );
            } elseif ($hasMethod && $hasData) {
                $this->mockObject->$methodName(
                    $this->mockInterfaceData[$interfaceName]
                );
            }
        }
    }

    /**
     * Returns targets for mock-creation (e.g. class-name and/or
     * interface names as defined) to hand over to Mockery
     *
     * @return array
     */
    protected function getMockTargetsAsString()
    {
        $targets = array($this->originalClassName);

        if ($this->shouldImplementInterfaces()) {
            $targets = array_merge($targets, $this->mockInterfaces);
        }

        $targetString = implode(', ', $targets);

        return $targetString;
    }
}
