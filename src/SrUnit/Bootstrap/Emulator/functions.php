<?php

use SrUnit\Mock\Registry;

if (false === function_exists('\oxNew')) {
    function oxNew($className) {
        return Registry::getInstance()->get(strtolower($className));
    }
}

