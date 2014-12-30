<?php

namespace SrUnit\Tests\Util\Filesystem;

use PhpUnitHelpers\DataProviderHelper;
use SrUnit\Util\Filesystem\Filesystem;

/**
 * Class VirtualFilesystemTest
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class FilesystemTest extends BaseFilesystemTest
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    protected function tearDown()
    {
        if (false === is_null($this->filesystem)) {
            $this->filesystem->tearDown();
        }
    }

    public function testTearDown()
    {
        $rootDir = __DIR__ . DIRECTORY_SEPARATOR . uniqid('filesystem-test');
        $fs = new Filesystem($rootDir);

        $this->assertFileExists($rootDir);
        $fs->tearDown();
        $this->assertFileNotExists($rootDir);
    }

    public static function getDataForCreateDirectory()
    {
        $provider = new DataProviderHelper(array(
            'Root-Directory',
            'Directory to create',
            'Expected directory path'
        ));

        $rootDir = __DIR__ . DIRECTORY_SEPARATOR . uniqid('filesystem-test');

        $provider
            ->addTestCase('Create one Directory')
                ->addData($rootDir)
                ->addData('simple')
                ->addData($rootDir . '/simple')
            ->addTestCase('Create one Directory with leading/trailing slash')
                ->addData($rootDir)
                ->addData('/simple/')
                ->addData($rootDir . '/simple')
            ->addTestCase('Create path')
                ->addData($rootDir)
                ->addData('path/to/dir')
                ->addData($rootDir . '/path/to/dir');

        return $provider->toArray();
    }

    /**
     * @dataProvider getDataForCreateDirectory
     * @depends testTearDown
     * @param $rootDir
     * @param $directoryToCreate
     * @param $expectedDirName
     */
    public function testCreateDirectory($rootDir, $directoryToCreate, $expectedDirName)
    {
        parent::testCreateDirectory($rootDir, $directoryToCreate, $expectedDirName);
    }

    public static function getDataForCreateFile()
    {
        $provider = new DataProviderHelper(array(
            'Root-Directory',
            'File to create',
            'Expected file path'
        ));

        $rootDir = __DIR__ . DIRECTORY_SEPARATOR . uniqid('filesystem-test');

        $provider
            ->addTestCase('Create simple file')
                ->addData($rootDir)
                ->addData('doc.txt')
                ->addData($rootDir . '/doc.txt')
            ->addTestCase('Create file with path')
                ->addData($rootDir)
                ->addData('/path/to/file/doc.txt')
                ->addData($rootDir . '/path/to/file/doc.txt');

        return $provider->toArray();
    }

    /**
     * @dataProvider getDataForCreateFile
     * @depends testTearDown
     * @param $rootDir
     * @param $fileToCreate
     * @param $expectedFileName
     */
    public function testCreateFile($rootDir, $fileToCreate, $expectedFileName)
    {
        parent::testCreateFile($rootDir, $fileToCreate, $expectedFileName);
    }

    /**
     * @depends testTearDown
     */
    public function testCreateSymlink()
    {
        $rootDir = __DIR__ . DIRECTORY_SEPARATOR . uniqid('filesystem-test');
        $fs = $this->getFilesystem($rootDir);

        $targetDir = $fs->createDirectory('target-dir');
        $symlink = $fs->createSymlink('linked-dir', $targetDir->getPathname());

        $this->assertTrue(is_link($symlink->getPathname()));
        $this->assertEquals($rootDir . DIRECTORY_SEPARATOR . 'target-dir', $symlink->getRealPath());
    }

    /**
     * @param $rootDir
     * @return Filesystem
     */
    protected function getFilesystem($rootDir)
    {
        $this->filesystem = new Filesystem($rootDir);
        return $this->filesystem;
    }
}
