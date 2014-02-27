<?php

namespace SrUnit\Mock\Builder;

use SrUnit\Mock\Factory;
use OutOfBoundsException;

/**
 * Class Builder
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Agentur für Neue Kommunikation
 * @package SrUnit\Mock\Builder
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class Builder
{
    /** @var AbstractProvisioner */
    protected $provisioner;

    /**
     * Constructor
     *
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * @return \Mockery\MockInterface|oxBase
     */
    public function getObject()
    {
        $object = Factory::mock($this->className);
        $object = $this->getProvisioner()->apply($object);

        return $object;
    }

    /**
     * Returns provisioner for specific class-name
     *
     * @return AbstractProvisioner
     * @throws \OutOfBoundsException
     */
    public function getProvisioner()
    {
        if (is_null($this->provisioner)) {
            $provisionerClassName = __NAMESPACE__ . "\\Provisioner" . $this->className . 'Provisioner';

            if (false === class_exists($provisionerClassName)) {
                throw new OutOfBoundsException(
                    sprintf('Could not load provisioner "%s".', $provisionerClassName)
                );
            }

            $this->provisioner = new $provisionerClassName;
        }

        return $this->provisioner;
    }
}