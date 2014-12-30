<?php

namespace SrUnit\Tests\Util\Filesystem;

use SrUnit\Util\Filesystem\FilesystemInterface;

/**
 * Class AbstractFilesystemTest
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package SrUnit\Tests\Util\Filesystem
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
abstract class BaseFilesystemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $rootDir
     * @param $directoryToCreate
     * @param $expectedDirName
     */
    public function testCreateDirectory($rootDir, $directoryToCreate, $expectedDirName)
    {
        $fs = $this->getFilesystem($rootDir);
        $directory = $fs->createDirectory($directoryToCreate);

        $this->assertEquals($expectedDirName, $directory->getPathname());
        $this->assertFileExists($directory->getPathname());
        $this->assertTrue($directory->isDir());
    }

    /**
     * @param $rootDir
     * @param $fileToCreate
     * @param $expectedFileName
     */
    public function testCreateFile($rootDir, $fileToCreate, $expectedFileName)
    {
        $fs = $this->getFilesystem($rootDir);
        $file = $fs->createFile($fileToCreate);

        $this->assertEquals($expectedFileName, $file->getPathname());
        $this->assertFileExists($file->getPathname());
        $this->assertTrue($file->isFile());
    }

    public function testCreateFileWithContent()
    {
        $fs = $this->getFilesystem('root');
        $file = $fs->createFile('doc.txt', 'the content');

        $this->assertEquals('the content', file_get_contents($file->getPathname()));
    }

    /**
     * @param $rootDir
     * @return FilesystemInterface
     */
    abstract protected function getFilesystem($rootDir);
}