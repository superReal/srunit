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
 * @copyright (C) superReal GmbH | Agentur für Neue Kommunikation
 * @package SrUnit\Mock
 * @author Jens Wiese <j.wiese AT superreal.de>
 * @author Thomas Oppelt <t.oppelt AT superreal.de>
 */
class Factory
{
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
    protected $shouldBeRegisteredForOxid = false;

    /**
     * @var bool
     */
    protected $shouldBeProvisioned = false;

    /**
     * Holds extended oxutilsobject factory
     *
     * @var SrOxUtilsObject
     */
    protected $sroxutilsobject = null;

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
     * Returns mock-object
     *
     * @return Mockery\MockInterface|CustomMockInterface
     */
    public function getMock()
    {
        $this->mockObject = $this->mock($this->getMockTargets());

        if ($this->shouldBeRegisteredForOxid) {
            $this->mockObject->shouldDeferMissing();
            Registry::set($this->originalClassName, $this->mockObject);
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
     * @return Mockery\MockInterface
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
    public function registerOxidObject()
    {
        if (null === $this->sroxutilsobject) {
            $this->sroxutilsobject = \oxNew('GetSrOxUtilsObject');
        }

        $this->mockClassName = self::$sroxutilsobject->getClassName(strtolower($this->originalClassName));
        $this->shouldRegisterOxidObject = true;

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
     * Apply data for defined interfaces (e.g. Iterator, ArrayAccess)
     */
    protected function applyDataForInterfaces()
    {
        foreach ($this->mockInterfaces as $interfaceName) {
            $methodName = 'implements' . $interfaceName;
            $hasMethod = method_exists($this->mockObject, $methodName);
            $hasData = isset($this->mockInterfaceData[$interfaceName]);

            if ($hasMethod && $hasData) {
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
    protected function getMockTargets()
    {
        $targets = array($this->mockClassName);

        if ($this->shouldImplementInterfaces()) {
            $targets = array_merge($targets, array($this->mockInterfaces));
        }

        return $targets;
    }

    /**
     * Proxy methods that delegates call to MockeryProxy
     *
     * @return mixed
     */
    protected function mock()
    {
        return call_user_func_array(
            array(__NAMESPACE__ . '\MockeryProxy', 'mock'),
            func_get_args()
        );
    }
}
