<?php

namespace SrUnit\Bootstrap;

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
        include __DIR__ . '/Emulation/functions.php';

        if (false === class_exists('\oxDb')) {
            class_alias('\SrUnit\Bootstrap\Emulation\oxDb', '\oxDb');
        }

        if (false === class_exists('\oxField')) {
            class_alias('\SrUnit\Bootstrap\Emulation\oxField', '\oxField');
        }

        if (false === class_exists('\oxRegistry')) {
            class_alias('\SrUnit\Bootstrap\Emulation\oxRegistry', '\oxRegistry');
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

    /**
     * Activate sR Unit OXID module
     *
     * @throws \InvalidArgumentException
     */
    protected function activateModule()
    {
        if (false === class_exists('\oxModule')) {
            throw new \InvalidArgumentException('Could not load sR Unit Module, because OXID is not available.');
        }

        $module = new \oxModule();
        $module->load('srunit');

        if (false === $module->isActive()) {
            $module->activate();
        }

        define('SRUNIT_TESTS', true);
    }

    /**
     * Deactivate sR Unit OXID module
     */
    protected function deactivateModule()
    {
        $module = new \oxModule();
        $module->load('srunit');
        if ($module->isActive()) {
            $module->deactivate();
        }
    }
}