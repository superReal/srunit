<?php

namespace SrUnit\Mock;

use Mockery\MockInterface;
use SrUnit\Mock\MockGenerator\CustomMockInterface;

/**
 * Class Mock
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Agentur für Neue Kommunikation
 * @package SrUnit\Mock
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class CustomMock implements CustomMockInterface
{
    protected $mock;

    /**
     * @param MockInterface $mock
     * @return CustomMock
     */
    public static function create(MockInterface $mock)
    {
        return new self($mock);
    }

    /**
     * @param MockInterface $mock
     */
    public function __construct(MockInterface $mock)
    {
        $this->mock = $mock;
    }

    /**
     * @inheritdoc
     */
    public function implementsIteratorInterface(array $data)
    {
        $iterator = new \stdClass();
        $iterator->data = $data;
        $iterator->position = 0;

        $this->mock->shouldReceive('count')->andReturn(count($iterator->data));
        $this->mock->shouldReceive('rewind')->andReturnUsing(function() use ($iterator) {
            $iterator->position = 0;
        });

        $this->mock->shouldReceive('valid')->andReturnUsing(function() use ($iterator) {
            return array_key_exists($iterator->position, $iterator->data);
        });

        $this->mock->shouldReceive('current')->andReturnUsing(function() use ($iterator) {
            return $iterator->data[$iterator->position];
        });

        $this->mock->shouldReceive('next')->andReturnUsing(function() use ($iterator) {
            $iterator->position++;
        });

        $this->mock->shouldReceive('key')->andReturnUsing(function() use ($iterator) {
            return $iterator->position;
        });

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function provideArrayAccess(array $data)
    {
        $container = new \stdClass();
        $container->data = $data;

        $this->mock->shouldReceive('offsetExists')->andReturnUsing(function($offset) use ($container) {
            return isset($container->data[$offset]);
        });

        $this->mock->shouldReceive('offsetGet')->andReturnUsing(function($offset) use ($container) {
            return isset($container->data[$offset]) ? $container->data[$offset] : null;
        });

        $this->mock->shouldReceive('offsetSet')->andReturnUsing(function($offset, $value) use ($container) {
            if (is_null($offset)) {
                $container->data[] = $value;
            } else {
                $container->data[$offset] = $value;
            }
        });

        $this->mock->shouldReceive('offsetUnset')->andReturnUsing(function($offset) use ($container) {
            unset($container->data[$offset]);
        });

        return $this;
    }
}