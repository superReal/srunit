<?php

namespace SrUnit;

use SrUnit\Bootstrap\DirectoryFinder;
use Composer\Autoload\ClassLoader;
use RuntimeException;
use SrUnit\Bootstrap\Emulator\Oxid;
use SrUnit\Bootstrap\SrUnitModule;

/**
 * Class Bootstrap
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package superreal/srunit
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class Bootstrap
{
    /** @var DirectoryFinder */
    protected $directoryFinder;

    /** @var  ClassLoader */
    protected $composerClassLoader;

    /** @var bool */
    protected $isOXIDLoaded = false;

    /** @var bool */
    protected $isOXIDMandatory = false;

    /** @var bool  */
    protected $isOXIDBypassed = false;


    /**
     * Creates new Bootstrap object
     *
     * @param string $testDir
     * @return Bootstrap
     */
    public static function create($testDir = null)
    {
        return new self($testDir);
    }

    /**
     * @param string $testDir
     */
    private function __construct($testDir = null)
    {
        $this->directoryFinder = $this->getDirectoryFinder($testDir);
    }

    /**
     * Enable loading of OXID bootstrapping
     *
     * @return $this
     */
    public function loadOXID()
    {
        $this->isOXIDMandatory = true;

        return $this;
    }

    /**
     * Bootstraps Unit Test Environment
     *
     * @throws \RuntimeException
     */
    public function bootstrap()
    {
        $this->loadComposerAutoloader();
        $this->loadOXIDFramework();
        $this->registerModuleAutoloader();

        if ($this->isOXIDMandatory() && !$this->isOXIDLoaded()) {
            throw new RuntimeException('Could not bootstrap test environment, due to a not loaded OXID framework.');
        }
    }

    /**
     * Returns whether or not OXID framework is set mandatory
     *
     * @return bool
     */
    protected function isOXIDMandatory()
    {
        return $this->isOXIDMandatory;
    }

    /**
     * Returns whether or not OXID framework was bootrapped
     *
     * @return bool
     */
    protected function isOXIDLoaded()
    {
        return $this->isOXIDLoaded;
    }

    /**
     * Returns whether or not OXID framework is bypassed
     *
     * @return bool
     */
    protected function isOXIDBypassed()
    {
        return $this->isOXIDBypassed();
    }

    /**
     * Returns Composer ClassLoader
     *
     * @return ClassLoader
     */
    protected function getComposerClassLoader()
    {
        return $this->composerClassLoader;
    }

    /**
     * Bootstraps composer-autoloader
     */
    protected function loadComposerAutoloader()
    {
        $path = $this->directoryFinder->getVendorDir() . '/autoload.php';

        if (file_exists($path)) {
            $this->composerClassLoader = require $path;
        }
    }

    /**
     * Boostraps OXID
     */
    protected function loadOXIDFramework()
    {
        if ($this->isOXIDMandatory()) {
            $path = $this->directoryFinder->getShopBaseDir() . 'bootstrap.php';

            if (file_exists($path)) {
                require_once $path;
                $this->isOXIDLoaded = true;
            }
        } else {
            Oxid::emulate();
        }
    }

    /**
     * Bootstraps all files defined in metadata.php
     * of current module
     */
    protected function registerModuleAutoloader()
    {
        $metadataFilePath = $this->directoryFinder->getModuleDir() . '/metadata.php';

        if (false === file_exists($metadataFilePath)) {
            return;
        }

        require_once $metadataFilePath;

        if (false === isset($aModule['files'])) {
            return;
        }
        $customLoader = function ($className) use ($aModule) {
            $className = strtolower($className);

            if (isset($aModule['files'][$className])) {
                $path = substr(
                    $aModule['files'][$className],
                    strpos($aModule['files'][$className], '/') + 1
                );

                require_once $path;
            }
        };

        spl_autoload_register($customLoader);
    }

    /**
     * @param $testDir
     * @return DirectoryFinder
     */
    protected function getDirectoryFinder($testDir)
    {
        return new DirectoryFinder($testDir);
    }
}