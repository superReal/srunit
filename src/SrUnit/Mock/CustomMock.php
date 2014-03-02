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
        $container->position = 0;

        $this->mock->shouldReceive('offsetExists')->andReturnUsing(function($offset) use ($data) {
            return isset($data[$offset]);
        });

        $this->mock->shouldReceive('offsetGet')->andReturnUsing(function($offset) use ($data) {
            return isset($data[$offset]) ? $data[$offset] : null;
        });
//
//
//
//        public function offsetSet($offset, $value) {
//        if (is_null($offset)) {
//            $this->container[] = $value;
//        } else {
//            $this->container[$offset] = $value;
//        }
//    }
//        public function offsetExists($offset) {
//        return isset($this->container[$offset]);
//    }
//        public function offsetUnset($offset) {
//        unset($this->container[$offset]);
//    }
//        public function offsetGet($offset) {
//        return isset($this->container[$offset]) ? $this->container[$offset] : null;
//    }

        return $this;
    }
}