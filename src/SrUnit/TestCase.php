<?php

namespace SrUnit;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_TestResult;
use SrUnit\Bootstrap\OxidLoader;
use SrUnit\Util\Filesystem\FilesystemInterface;

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
     * @param string $rootDir
     * @return FilesystemInterface
     */
    public function createFilesystem($rootDir, $type = FilesystemInterface::VIRTUAL)
    {
        $classname = '\SrUnit\Util\Filesystem\\' . $type;

        if (false === class_exists($classname)) {
            throw new \InvalidArgumentException(sprintf('Could not create filesystem "%s".', $type));
        }

        return new $classname($rootDir);
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