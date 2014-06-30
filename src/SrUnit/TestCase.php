<?php

namespace SrUnit;

use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit_Framework_TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;

/**
 * Class TestCase extends PHPUnit default testcase
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Agentur für Neue Kommunikation
 * @package SrUnit
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Returns virtual filesystem for given root directory (based on vfsStream)
     *
     * @param string $rootDirName
     * @param int $permissions
     * @param array $structure
     * @return vfsStreamDirectory
     */
    public function createVirtualFileSystem($rootDirName, $permissions = null, array $structure = array())
    {
        return vfsStream::setup($rootDirName, $permissions, $structure);
    }

    /**
     * @param string $name
     * @param int $permissions
     * @return vfsStreamFile
     */
    public function createVirtualFile($name, $permissions = null)
    {
        return new vfsStreamFile($name, $permissions);
    }
}