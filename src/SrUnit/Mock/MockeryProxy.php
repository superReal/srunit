<?php

namespace SrUnit\Mock;

use Mockery;
use Mockery\Generator\CachingGenerator;
use Mockery\Generator\StringManipulation\Pass\CallTypeHintPass;
use Mockery\Generator\StringManipulation\Pass\ClassNamePass;
use Mockery\Generator\StringManipulation\Pass\ClassPass;
use Mockery\Generator\StringManipulation\Pass\InstanceMockPass;
use Mockery\Generator\StringManipulation\Pass\InterfacePass;
use Mockery\Generator\StringManipulation\Pass\MethodDefinitionPass;
use Mockery\Generator\StringManipulation\Pass\RemoveBuiltinMethodsThatAreFinalPass;
use Mockery\Generator\StringManipulationGenerator;
use Mockery\Loader\EvalLoader;
use SrUnit\Bootstrap;
use SrUnit\Mock\Builder\Builder;
use SrUnit\Mock\MockGenerator\Pass\CustomMockMethodPass;

/**
 * Class Factory
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Agentur für Neue Kommunikation
 * @package SrUnit\Mock
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class MockeryProxy extends Mockery
{
    /**
     * @var \Mockery\Generator\Generator
     */
    protected static $_generator;

    /**
     * @return Mockery\MockInterface | MockGenerator\CustomMockInterface
     */
    public static function mock()
    {
        $args = func_get_args();
        return call_user_func_array(array(self::getContainer(), 'mock'), $args);
    }

    /**
     * Get the container
     */
    public static function getContainer()
    {
        if (self::$_container) {
            return self::$_container;
        }

        return self::$_container = new Mockery\Container(self::getGenerator(), self::getLoader());
    }

    /**
     * @return CachingGenerator|Mockery\Generator\Generator|StringManipulationGenerator
     */
    public static function getGenerator()
    {
        if (self::$_generator) {
            return self::$_generator;
        }

        self::$_generator = self::getDefaultGenerator();

        return self::$_generator;
    }

    /**
     * @return CachingGenerator|StringManipulationGenerator
     */
    public static function getDefaultGenerator()
    {
        $generator = new StringManipulationGenerator(array(
            new CallTypeHintPass(),
            new ClassPass(),
            new ClassNamePass(),
            new InstanceMockPass(),
            new InterfacePass(),
            new MethodDefinitionPass(),
            new RemoveBuiltinMethodsThatAreFinalPass(),
            new CustomMockMethodPass(),
        ));

        $generator = new CachingGenerator($generator);

        return $generator;
    }

    /**
     * Method acts as proxy method to mock-object.
     *
     * @param string $name
     * @param mixed $target
     * @return Mockery\MockInterface
     */
    public function getMock($name, $target)
    {
        $args = func_get_args();
        if (is_null($name)) {
            array_shift($args);
            return call_user_func_array(array('\SrUnit\Mock\MockeryProxy', 'mock'), $args);

        } else {
            return $this->namedMock($name, $target);
        }
    }
}