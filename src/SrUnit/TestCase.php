<?php

namespace SrUnit;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_TestResult;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use SrUnit\Bootstrap\OxidLoader;

/**
 * Class TestCase extends PHPUnit default testcase
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package superreal/srunit
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Whether test needs OXID framework or not
     *
     * @var bool
     */
    protected $needsOXID = false;

    /**
     * @param boolean $needsOXID
     */
    public function setNeedsOXID($needsOXID)
    {
        $this->needsOXID = $needsOXID;
    }

    /**
     * @return boolean
     */
    public function needsOXID()
    {
        return $this->needsOXID;
    }

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

    /**
     * {@inheritdoc}
     */
    public function run(PHPUnit_Framework_TestResult $result = null)
    {
        if ($this->needsOXID()) {
            OxidLoader::getInstance()->load();
        } else {
            OxidLoader::getInstance()->emulate();
        }

        $return = parent::run($result);

        return $return;
    }
}