<?php

namespace SrUnit\Util\Filesystem;

/**
 * Interface FilesystemInterface
 * @package SrUnit\Util
 */
interface FilesystemInterface
{
    /**
     * Available Filesystems
     */
    const VIRTUAL = 'VirtualFilesystem';
    const PHYSICAL = 'Filesystem';

    /**
     * @param string $rootDirectory
     */
    public function __construct($rootDirectory);

    /**
     * @param string $path
     * @return \SplFileInfo
     */
    public function createDirectory($path);

    /**
     * @param string $path
     * @return \SplFileInfo
     */
    public function createFile($path, $content = null);

    /**
     * Remove complete directory structure (incl. root-dir)
     */
    public function tearDown();
}