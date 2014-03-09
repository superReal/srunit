<?php

namespace SrUnit\Mock;

use Faker\Factory as FakerFactory;
use Faker\Generator;
use Mockery\MockInterface;
use oxField;

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

    /** @var string */
    protected $tableName;

    /**
     * @param Generator $generator
     */
    public function __construct(Generator $generator = null)
    {
        if (is_null($generator)) {
            $generator = FakerFactory::create();
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
            $oxidKey = sprintf('%s__%s', $this->tableName, $key);
            $object->$oxidKey = $this->getOxField($value);
        }

        $this->applyStubs($object);
    }

    /**
     * Creates new oxField object for given value
     * (either the real object or if not available a mock)
     *
     * @param mixed $value
     * @return \oxField
     */
    protected function getOxField($value)
    {
        if (class_exists('\oxField')) {
            $oxField = new \oxField($value);
        } else {
            $oxField = \Mockery::mock('\oxField')->shouldIgnoreMissing();
            $oxField->shouldReceive('getRawValue')->andReturn($value);
            $oxField->value = $value;
            $oxField->rawValue = $value;
        }

        return $oxField;
    }


    /**
     * Applies stub-definition to mock object (if provided)
     */
    protected function applyStubs(MockInterface $object)
    {}

    /**
     * Returns default values by given fields
     *
     * @param array $fields
     * @return array
     */
    protected function getDefaultFields(array $fields)
    {
        $defaults = array(
            'oxid' => $this->generator->md5,
            'oxparentid' => '',
            'oxactive' => '1',
            'oxtimestamp' => $this->generator->dateTime,
            'oxshopid' => 'oxbaseshop',
            'oxsort' => $this->generator->randomNumber(),
            'oxactivefrom' => '0000-00-00 00:00:00',
            'oxactiveto' => '0000-00-00 00:00:00',
            'oxdesc' => $this->generator->text(255),
            'oxshortdesc' => $this->generator->text(50),
        );

        foreach ($defaults as $key => $value) {
            if (false === in_array($key, $fields)) {
                unset($defaults[$key]);
            }
        }

        return $defaults;
    }

    /**
     * Returns db-fields with dummy data
     *
     * @return array
     */
    protected function getFieldMapping()
    {
        return array();
    }
} 