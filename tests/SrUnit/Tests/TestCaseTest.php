<?php

namespace SrUnit\Tests;

use SrUnit\TestCase;
use SrUnit\Util\Filesystem\FilesystemInterface;

/**
 * Class TestCaseTest
 *
 * @link http://www.superReal.de
 * @copyright (C) superReal GmbH | Create Commerce
 * @package SrUnit\Tests
 * @author Jens Wiese <j.wiese AT superreal.de>
 */
class TestCaseTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFilesystem()
    {
        $case = new TestCase();
        $fs = $case->createFilesystem('foo', FilesystemInterface::PHYSICAL);
        $this->assertInstanceOf('\SrUnit\Util\Filesystem\Filesystem', $fs);
    }

    public function testCreateVirtualFilesystem()
    {
        $case = new TestCase();
        $fs = $case->createFilesystem('foo', FilesystemInterface::VIRTUAL);
        $this->assertInstanceOf('\SrUnit\Util\Filesystem\VirtualFilesystem', $fs);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Could not create filesystem "unknown".
     */
    public function testCreateUnknownFilesystem()
    {
        $case = new TestCase();
        $case->createFilesystem('foo', 'unknown');
    }
}
