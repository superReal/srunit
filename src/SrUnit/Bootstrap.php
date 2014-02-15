<?php

namespace SrUnit;

use Composer\Autoload\ClassLoader;
use RuntimeException;

/**
 * Class Bootstrap
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Agentur für Neue Kommunikation
 * @package superreal/srunit
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class Bootstrap
{
    /** @var array */
    protected $requiredFiles = array();

    /** @var  ClassLoader */
    protected $composerClassLoader;

    /** @var bool */
    protected $isOxidLoaded = false;

    /** @var bool */
    protected $isOxidMandatory = false;

    /**
     * Creates new Bootstrap object
     *
     * @return Bootstrap
     */
    public static function create()
    {
        return new self();
    }


    public function addRequiredFile($path)
    {
        $this->requiredFiles[] = $path;

        return $this;
    }

    public function makeOxidMandatory()
    {
        $this->oxidIsMandatory = true;

        return $this;
    }

    /**
     * Bootstraps Unit Test Environment
     *
     * @throws \RuntimeException
     */
    public function bootstrap()
    {
        $this->bootstrapComposerAutoloader();
        $this->bootstrapOxidFramework();
        $this->bootstrapRequiredFiles();

        if ($this->isOxidFrameworkMandatory && !$this->isOxidLoaded()) {
            throw new RuntimeException('Could not bootstrap test environment, due to a not loaded Oxid framework.');
        }
    }

    /**
     * Returns whether or not OXID framework was bootrapped
     *
     * @return bool
     */
    public function isOxidLoaded()
    {
        return $this->isOxidLoaded;
    }

    /**
     * Returns Composer ClassLoader
     *
     * @return ClassLoader
     */
    public function getComposerClassLoader()
    {
        return $this->composerClassLoader;
    }

    /**
     * Bootstraps composer-autoloader
     */
    private function bootstrapComposerAutoloader()
    {
        $path = __DIR__ . '/../../../vendor/autoloader.php';

        if (file_exists($path)) {
            $this->composerClassLoader = require $path;
        }
    }

    /**
     * Bootraps Oxid
     */
    private function bootstrapOxidFramework()
    {
        if ($this->isOxidMandatory) {
            $path = __DIR__ . '/../../../bootstrap.php';

            if (file_exists($path)) {
                require_once $path;
                $this->isOxidLoaded = true;
            }
        }
    }

    /**
     * Bootstraps all defined required files (if exist)
     */
    private function bootstrapRequiredFiles()
    {
        foreach ($this->requiredFiles as $path) {
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }
} 