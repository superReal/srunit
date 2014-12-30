<?php

namespace SrUnit\Tests\Util\Filesystem;

use PhpUnitHelpers\DataProviderHelper;
use SrUnit\Util\Filesystem\VirtualFilesystem;

/**
 * Class VirtualFilesystemTest
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class VirtualFilesystemTest extends BaseFilesystemTest
{
    public static function getDataForCreateDirectory()
    {
        $provider = new DataProviderHelper(array(
            'Root-Directory',
            'Directory to create',
            'Expected directory path'
        ));

        $provider
            ->addTestCase('Create one Directory')
                ->addData('/tmp')
                ->addData('simple')
                ->addData('vfs://tmp/simple')
            ->addTestCase('Create one Directory with leading/trailing slash')
                ->addData('/tmp')
                ->addData('/simple/')
                ->addData('vfs://tmp/simple')
            ->addTestCase('Create path')
                ->addData('/tmp')
                ->addData('path/to/dir')
                ->addData('vfs://tmp/path/to/dir');

        return $provider->toArray();
    }

    /**
     * @dataProvider getDataForCreateDirectory
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

        $provider
            ->addTestCase('Create simple file')
                ->addData('/tmp')
                ->addData('doc.txt')
                ->addData('vfs://tmp/doc.txt')
            ->addTestCase('Create file with path')
                ->addData('/tmp')
                ->addData('/path/to/file/doc.txt')
                ->addData('vfs://tmp/path/to/file/doc.txt');

        return $provider->toArray();
    }

    /**
     * @dataProvider getDataForCreateFile
     * @param $rootDir
     * @param $fileToCreate
     * @param $expectedFileName
     */
    public function testCreateFile($rootDir, $fileToCreate, $expectedFileName)
    {
        parent::testCreateFile($rootDir, $fileToCreate, $expectedFileName);
    }

    /**
     * @param $rootDir
     * @return VirtualFilesystem
     */
    protected function getFilesystem($rootDir)
    {
        return new VirtualFilesystem($rootDir);
    }
}
