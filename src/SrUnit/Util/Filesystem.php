<?php

namespace SrUnit\Util;

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
class Filesystem
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
        $this->rootDirectory = rtrim($rootDirectory, '/');

        if (false === is_dir($rootDirectory)) {
            mkdir($this->rootDirectory, 0777, true);
        }
    }

    /**
     * @param string $path
     * @return string Created directory
     */
    public function createDirectory($path)
    {
        $path = $this->getFullpath($path);

        if (is_dir($path)) {
            return realpath($path);
        }

        if (mkdir($path, 0777, true)) {
            return realpath($path);
        }
    }

    /**
     * @param string $path
     * @return string Created file
     */
    public function createFile($path, $content = null)
    {
        $this->createDirectory(dirname($path));

        $path = $this->getFullpath($path);

        if (touch($path)) {
            file_put_contents($path, $content);
            return realpath($path);
        }
    }

    /**
     * @param string $path
     * @param int $permissions
     * @return bool
     */
    public function chmod($path, $permissions)
    {
        $path = $this->getFullpath($path);

        return chmod($path, $permissions);
    }

    /**
     * @param string $path
     * @param int $timestamp
     * @return bool
     */
    public function setModificationTime($path, $timestamp)
    {
        $path = $this->getFullpath($path);

        return touch($path, $timestamp);
    }

    /**
     * @param string $link
     * @param string $target
     * @return string Created symlink
     */
    public function createSymlink($link, $target)
    {
        $link = $this->getFullpath($link);

        if (symlink($target, $link)) {
            return $link;
        }
    }

    /**
     * Remove complete directory structure (incl. root-dir)
     */
    public function removeFilesystem()
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