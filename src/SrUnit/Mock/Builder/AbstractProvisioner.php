<?php

namespace SrUnit\Mock\Builder;

use Faker\Factory;
use Faker\Generator;

/**
 * Class AbstractProvisioner
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Agentur für Neue Kommunikation
 * @package SrUnit\Builder\Provisioner
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
abstract class AbstractProvisioner
{
    /** @var Generator */
    protected $generator;

    /** @var array */
    protected $mapping = array();

    /**
     * @param Generator $generator
     */
    public function __construct(Generator $generator = null)
    {
        if (is_null($generator)) {
            $generator = Factory::create();
            $generator->seed('12345');
        }

        $this->generator = $generator;
    }

    /**
     * Applies provisioning rules to given object
     *
     * @param oxBase $object
     * @return oxBase
     */
    public function apply($object)
    {
        foreach ($this->getFieldMapping() as $key => $value) {
            $object->$key = $value;
        }

        return $object;
    }

    /**
     * Returns db-fields with dummy data
     *
     * Format: 'oxarticles__oxtitle' => 'Polo-Shirt'
     *
     * @return array
     */
    abstract protected function getFieldMapping();
} 