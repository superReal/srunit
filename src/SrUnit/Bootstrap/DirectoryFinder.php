<?php

namespace SrUnit\Bootstrap;

/**
 * Class DirectoryFinder
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package SrUnit\Bootstrap
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class DirectoryFinder
{
    /**
     * @var string
     */
    protected $testDir;

    /**
     * @var string
     */
    protected $shopBaseDir;
    /**
     * @var string
     */
    protected $vendorDir;
    /**
     * @var string
     */
    protected $moduleDir;

    /**
     * @param string $testDir
     */
    public function __construct($testDir)
    {
        $this->setTestDir($testDir);
        $this->retrieveShopBaseDir();
        $this->retrieveVendorDir();
        $this->retrieveModuleDir();
    }

    /**
     * @return string
     */
    public function getShopBaseDir()
    {
        return $this->shopBaseDir;
    }

    /**
     * @return string
     */
    public function getVendorDir()
    {
        return $this->vendorDir;
    }

    /**
     * @return string
     */
    public function getModuleDir()
    {
        return $this->moduleDir;
    }

    /**
     * @return string
     */
    public function getTestDir()
    {
        return $this->testDir;
    }

    /**
     * @param string $directory
     * @return $this
     * @throws \InvalidArgumentException
     */
    protected function setTestDir($directory)
    {
        $this->testDir = rtrim($directory, DIRECTORY_SEPARATOR);

        if (false === is_dir($this->testDir)) {
            throw new \InvalidArgumentException(
                sprintf('Test directory "%s" does not exists.', $directory)
            );
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function retrieveShopBaseDir()
    {
        $directories = array(
            $this->getTestDir() . '/../../..',
            $this->getTestDir() . '/../../../..',
        );

        foreach ($directories as $dir) {
            if (is_dir($dir) && file_exists($dir . '/oxseo.php')) {
                $this->shopBaseDir = $this->normalizePath($dir);
                return;
            }
        }
    }

    /**
     * @return string
     */
    protected function retrieveVendorDir()
    {
        $directories = array(
            $this->getTestDir() . '/../vendor',
            $this->getTestDir() . '/../../../vendor',
            $this->getTestDir() . '/../../../../vendor',
        );

        foreach ($directories as $dir) {
            if (is_dir($dir) && file_exists($dir . '/composer/autoload_classmap.php')) {
                $this->vendorDir = $this->normalizePath($dir);
                return;
            }
        }
    }

    /**
     * @return string
     */
    protected function retrieveModuleDir()
    {
        $this->moduleDir = $this->normalizePath($this->getTestDir() . '/..');
    }

    /**
     * Normalizes given path
     *
     * @param string $path
     * @return string
     */
    protected function normalizePath($path)
    {
        $streamScheme = parse_url($path, PHP_URL_SCHEME);
        if ($streamScheme) {
            $path = str_replace($streamScheme . '://', '', $path);
        }

        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = array();

        foreach ($parts as $part) {
            if ('.' == $part) {
                continue;
            }
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }

        $resolvedRealPath = DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $absolutes);

        if ($streamScheme) {
            $resolvedRealPath = $streamScheme . ':/' . $resolvedRealPath;
        }

        return $resolvedRealPath;
    }
}