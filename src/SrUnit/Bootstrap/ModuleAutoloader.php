<?php

namespace SrUnit\Bootstrap;

/**
 * Class CustomAutoloader
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package SrUnit\Bootstrap
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class ModuleAutoloader
{
    /**
     * Holds class-to-path mappings
     *
     * @var array
     */
    protected $classmap = array();

    /**
     * @param array $metadataFiles
     * @throws \InvalidArgumentException
     */
    public function __construct(array $metadataFiles)
    {
        $aModule = array(); // will be overridden on loading of metadata.php

        foreach ($metadataFiles as $filepath) {
            if (false === file_exists($filepath)) {
                continue;
            }

            require_once $filepath;

            $modulePath = dirname($filepath);
            $extendedClasses = array();
            $definedClasses = array();

            if (isset($aModule['extend'])) {
                $extendedClasses = $this->retrieveExtendedClasses($modulePath, $aModule['extend']);
            }

            if (isset($aModule['files'])) {
                $definedClasses = $this->retrieveDefinedClasses($modulePath, $aModule['files']);
            }

            $this->classmap = array_merge($this->classmap, $extendedClasses, $definedClasses);
        }
    }

    /**
     * Load-method to be register for autoloading
     *
     * @param string $classname
     */
    public function load($classname)
    {
        $classname = strtolower($classname);
        if (isset($this->classmap[$classname])) {
            require_once $this->classmap[$classname];
        }
    }

    /**
     * @param string $modulePath
     * @param array $files
     * @return array
     */
    protected function retrieveDefinedClasses($modulePath, array $files)
    {
        $classmap = array();

        foreach ($files as $classanme => $filepath) {
            $classanme = strtolower($classanme);
            $pathToClass = realpath($modulePath . '/../' . $filepath);

            if ($pathToClass) {
                $classmap[$classanme] = $pathToClass;
            }
        }

        return $classmap;
    }

    /**
     * @param string $modulePath
     * @param array $files
     * @return array
     */
    protected function retrieveExtendedClasses($modulePath, array $files)
    {
        $classmap = array();

        foreach ($files as $filepath) {
            $classanme = basename($filepath);
            $pathToClass = realpath($modulePath . '/../' . $filepath . '.php');

            if ($pathToClass) {
                $classmap[$classanme] = $pathToClass;
            }
        }

        return $classmap;
    }

} 