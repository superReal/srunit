<?php

namespace SrUnit\Bootstrap;

use SrUnit\Mock\Registry;

/**
 * Class OxidLoader
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package superreal/srunit
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class OxidLoader
{
    /**
     * @var OxidLoader
     */
    static protected $instance;

    /**
     * @var DirectoryFinder
     */
    protected $directoryFinder;

    /**
     * @var bool
     */
    protected $isLoaded = false;

    /**
     * @var bool
     */
    protected $isEmulated = false;

    /**
     * @return OxidLoader
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * @param DirectoryFinder $finder
     * @return $this
     */
    public function setDirectoryFinder(DirectoryFinder $finder)
    {
        $this->directoryFinder = $finder;

        return $this;
    }

    /**
     *
     */
    public function load()
    {
        $path = $this->directoryFinder->getShopBaseDir() . 'bootstrap.php';

        if (file_exists($path)) {
            require_once $path;
            $this->isLoaded = true;
        }
    }

    public function emulate()
    {
        if (false === function_exists('oxNew')) {
            function oxNew($className) {
                return Registry::getInstance()->get(strtolower($className));
            }
        }

        if (false === class_exists('\oxDb')) {
            class_alias('\SrUnit\Bootstrap\Emulation\oxDb', '\oxDb');
        }

        $this->isEmulated = true;
    }

    /**
     * @return bool
     */
    public function isLoaded()
    {
        return $this->isLoaded;
    }

    /**
     * @return bool
     */
    public function isEmulated()
    {
        return $this->isEmulated;
    }

} 