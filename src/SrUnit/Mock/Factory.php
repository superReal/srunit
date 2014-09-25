<?php

namespace SrUnit\Mock;

use Mockery;
use SrUnit\Mock\AbstractProvisioner;
use SrUnit\Mock\MockGenerator\CustomMockInterface;
use OutOfBoundsException;
use SrOxUtilsObject;

/**
 * Class Factory
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package SrUnit\Mock
 * @author Thomas Oppelt <t.oppelt AT superreal.de>
 * @author Jens Wiese <j.wiese AT superreal.de>
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
    protected $shouldBeRegisteredForOxNew = false;

    /**
     * @var bool
     */
    protected $shouldBeProvisioned = false;

    /**
     * @var AbstractProvisioner
     */
    protected $provisioner;

    /**
     * @param string $className
     * @throws Exception
     * @return Factory
     */
    public static function create($className)
    {
        if (false === is_string($className)) {
            throw new Exception(
                'Could not create mock. You have to provide a class-name when using ' . __METHOD__ . '.'
            );
        }

        return new self($className);
    }

    /**
     * @param string $className
     * @return Factory
     * @throws Exception
     */
    public static function createParentClass($className)
    {
        if (false === is_string($className)) {
            throw new Exception(
                'Could not create parent class. You have to provide a class-name when using ' . __METHOD__ . '.'
            );
        }

        return new self('overload:' . $className);
    }

    /**
     * @param object $actualObject
     * @throws Exception
     * @return Factory
     */
    public static function createFromObject($actualObject)
    {
        if (false === is_object($actualObject)) {
            throw new Exception(
                'Could not create mock. You have to provide an object when using ' . __METHOD__ . '.'
            );
        }

        return new self(get_class($actualObject), $actualObject);
    }

    /**
     * @param string $className
     * @param object $actualObject
     */
    private function __construct($className, $actualObject = null)
    {
        $this->originalClassName = $className;
        $this->mockClassName = $className;
        $this->actualObject = $actualObject;
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
     * Returns mock-object
     *
     * @return Mockery\MockInterface|CustomMockInterface
     */
    public function getMock()
    {
        if (is_null($this->actualObject)) {
            $this->mockObject = $this->getMockeryProxy()->getMock($this->getMockTargetsAsString());
        } else {
            $this->mockObject = $this->getMockeryProxy()->getMock($this->actualObject);
        }

        if ($this->shouldBeRegisteredForOxNew) {
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
     *
     * @return $this
     */
    public function useProvisioning()
    {
        $this->shouldBeProvisioned = true;

        return $this;
    }

    /**
     * Enables registration of mock object for OXIDs oxNew()
     *
     * @return $this
     */
    public function registerForOxNew()
    {
        $this->mockClassName = strtolower($this->originalClassName);
        $this->shouldBeRegisteredForOxNew = true;

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
            $this->registry = Registry::getInstance();
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
            $interfaceNameWithoutNamespace = explode('\\', $interfaceName);
            $methodName = 'implements' . array_pop($interfaceNameWithoutNamespace);

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
     * @return string
     */
    protected function getMockTargetsAsString()
    {
        $targets = array($this->mockClassName);

        if ($this->shouldImplementInterfaces()) {
            $targets = array_merge($targets, $this->mockInterfaces);
        }

        $targetString = implode(', ', $targets);

        return $targetString;
    }
}
