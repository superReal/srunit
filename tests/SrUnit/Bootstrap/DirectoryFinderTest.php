<?php

namespace SrUnit\Bootstrap;

use org\bovigo\vfs\vfsStream;

class DirectoryFinderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Test directory "does-not-exists" does not exists.
     */
    public function testNotExistingDirLeadsToException()
    {
        new DirectoryFinder('does-not-exists');
    }

    public function testFindDirectories_WhenModuleIsStandalone()
    {
        $directoryStructure = array(
            'testmodule' => array(
                'tests' => array(),
                'modules' => array(
                    'srunit' => array()
                ),
                'vendor' => array(
                    'composer' => array(
                        'autoload_classmap.php' => ''
                    )
                ),
            )
        );

        $finder = new DirectoryFinder(
            $this->setUpFilesystem($directoryStructure) . '/testmodule/tests'
        );

        $this->assertNull($finder->getShopBaseDir(), 'Expected shop-base-dir is not correct.');
        $this->assertEquals("vfs://project/testmodule", $finder->getModuleDir(), 'Expected module-dir is not correct.');
        $this->assertEquals("vfs://project/testmodule/tests", $finder->getTestDir(), 'Expected test-dir is not correct.');
        $this->assertEquals("vfs://project/testmodule/vendor", $finder->getVendorDir(), 'Expected vendor-dir is not correct.');
    }

    public function testFindDirectories_WhenModuleIsInstalled()
    {
        $directoryStructure = array(
            'www' => array(
                'oxseo.php' => '',
                'modules' => array(
                    'testmodule' => array(
                        'tests' => array()
                    )
                ),
                'vendor' => array(
                    'composer' => array(
                        'autoload_classmap.php' => ''
                    )
                ),
            )
        );

        $finder = new DirectoryFinder(
            $this->setUpFilesystem($directoryStructure) . '/www/modules/testmodule/tests'
        );

        $this->assertEquals("vfs://project/www", $finder->getShopBaseDir(), 'Expected shop-base-dir is not correct.');
        $this->assertEquals("vfs://project/www/modules/testmodule", $finder->getModuleDir(), 'Expected module-dir is not correct.');
        $this->assertEquals("vfs://project/www/modules/testmodule/tests", $finder->getTestDir(), 'Expected test-dir is not correct.');
        $this->assertEquals("vfs://project/www/vendor", $finder->getVendorDir(), 'Expected vendor-dir is not correct.');
    }

    public function testFindDirectories_WhenModuleIsInstalledInSubdirectory()
    {
        $directoryStructure = array(
            'www' => array(
                'oxseo.php' => '',
                'modules' => array(
                    'subdir' => array(
                        'testmodule' => array(
                            'tests' => array()
                        )
                    )
                ),
                'vendor' => array(
                    'composer' => array(
                        'autoload_classmap.php' => ''
                    )
                ),
            )
        );

        $finder = new DirectoryFinder(
            $this->setUpFilesystem($directoryStructure) . '/www/modules/subdir/testmodule/tests'
        );

        $this->assertEquals("vfs://project/www", $finder->getShopBaseDir(), 'Expected shop-base-dir is not correct.');
        $this->assertEquals("vfs://project/www/modules/subdir/testmodule", $finder->getModuleDir(), 'Expected module-dir is not correct.');
        $this->assertEquals("vfs://project/www/modules/subdir/testmodule/tests", $finder->getTestDir(), 'Expected test-dir is not correct.');
        $this->assertEquals("vfs://project/www/vendor", $finder->getVendorDir(), 'Expected vendor-dir is not correct.');
    }

    /**
     * Setups virtual filesystem
     *
     * @param array $structure
     * @return string
     */
    protected function setUpFilesystem(array $structure)
    {
        return vfsStream::setup('project', null, $structure)->url();
    }

}
