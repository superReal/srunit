<?php

namespace SrUnit\Util\Filesystem;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;

/**
 * Class VirtualFilesystem
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package SrUnit\Util
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class VirtualFilesystem implements FilesystemInterface
{
    /**
     * @var vfsStreamDirectory
     */
    protected $rootDirectory;

    /**
     * @param string $rootDirectory
     */
    public function __construct($rootDirectory)
    {
        $this->rootDirectory = vfsStream::setup($rootDirectory);
    }

    /**
     * @param string $path
     * @return \SplFileInfo
     */
    public function createDirectory($path)
    {
        $directory = $this->createPath($path);

        return new \SplFileInfo($directory->url());
    }

    /**
     * @param string $path
     * @return \SplFileInfo
     */
    public function createFile($path, $content = null)
    {
        $file = new vfsStreamFile(basename($path));
        $file->setContent($content);
        $this->createPath(dirname($path))->addChild($file);

        return new \SplFileInfo($file->url());
    }

    /**
     * @param string $path
     * @param int $permissions
     * @return bool
     */
    public function chmod($path, $permissions)
    {
        chmod($path, $permissions);
    }

    /**
     * @param string $path
     * @param int $timestamp
     * @return bool
     */
    public function setModificationTime($path, $timestamp)
    {
        touch($path, $timestamp);
    }

    /**
     * Remove complete directory structure (incl. root-dir)
     */
    public function tearDown()
    {
        return;
    }

    /**
     * @param $path
     * @return vfsStreamDirectory
     */
    protected function createPath($path)
    {
        $parentDirectory = $this->rootDirectory;
        $dirnames = array_filter(explode(DIRECTORY_SEPARATOR, $path));

        foreach ($dirnames as $dirname) {
            if ($dirname == '.') {
                continue;
            }

            $directory = new vfsStreamDirectory($dirname);
            $parentDirectory->addChild($directory);
            $parentDirectory = $directory;
        }

        return $parentDirectory;
    }
}