<?php

namespace SrUnit\Mock\MockGenerator;

/**
 * Interface CustomMockInterface
 * @package SrUnit\Mock\MockGenerator
 */
interface CustomMockInterface
{
    /**
     * @param array $data
     * @return CustomMockInterface
     */
    public function implementsIteratorInterface(array $data);

    /**
     * @param array $data
     * @return CustomMockInterface
     */
    public function implementsArrayAccess(array $data);

} 