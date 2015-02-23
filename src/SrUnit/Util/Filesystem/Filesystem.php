<?php

namespace SrUnit\Util\Filesystem;

use SplFileInfo;

/**
 * Class Filesystem
 *
 * Eases generation of test filesystem structure, where a physical representation is needed
 * (e.g. because of symlinks etc.)
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package SrUnit\Util
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class Filesystem implements FilesystemInterface
{
    /**
     * @var string
     */
    protected $rootDirectory;

    /**
     * @param string $rootDirectory
     */
    public function __construct($rootDirectory)
    {
        if (false === realpath($rootDirectory)) {
            $rootDirectory = tempnam(sys_get_temp_dir(), __CLASS__);
        }

        $this->rootDirectory = rtrim($rootDirectory, '/');

        if (false === is_dir($rootDirectory)) {
            mkdir($this->rootDirectory, 0777, true);
        }
    }

    public function __destruct()
    {
        $this->tearDown();
    }

    /**
     * @return string
     */
    public function getRootDirectory()
    {
        return $this->rootDirectory;
    }

    /**
     * @param string $path
     * @return SplFileInfo
     */
    public function createDirectory($path)
    {
        $path = $this->getFullpath($path);

        if (is_dir($path)) {
            return new SplFileInfo($path);
        }

        if (mkdir($path, 0777, true)) {
            return new SplFileInfo($path);
        }
    }

    /**
     * @param string $path
     * @return SplFileInfo
     */
    public function createFile($path, $content = null)
    {
        $this->createDirectory(dirname($path));

        $path = $this->getFullpath($path);

        if (touch($path)) {
            file_put_contents($path, $content);
            return new SplFileInfo($path);
        }
    }

    /**
     * @param string $link
     * @param string $target
     * @return SplFileInfo
     */
    public function createSymlink($link, $target)
    {
        $link = $this->getFullpath($link);

        if (symlink($target, $link)) {
            return new SplFileInfo($link);
        }
    }

    /**
     * Remove complete directory structure (incl. root-dir)
     */
    public function tearDown()
    {
        exec('rm -rf ' . $this->rootDirectory);
    }

    /**
     * Returns full path (incl. test-root-directory)
     *
     * @param $path
     * @return string
     */
    protected function getFullpath($path)
    {
        return $this->rootDirectory . DIRECTORY_SEPARATOR . trim($path, '/');
    }
} 