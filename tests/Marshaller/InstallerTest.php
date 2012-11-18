<?php

/**
 * \Marshaller\InstallerTest
 *
 * @category PHP
 * @package Marshaller
 * @subpackage Tests
 * @author Matthias Molitor <matthias@matthimatiker.de>
 * @copyright 2012 Matthias Molitor
 * @license http://www.opensource.org/licenses/BSD-3-Clause BSD License
 * @link https://github.com/Matthimatiker/PackageMarshaller
 * @since 10.11.2012
 */

namespace Marshaller;

/**
 * Initializes the test environment.
 */
require_once(dirname(__FILE__) . '/bootstrap.php');

/**
 * Tests the installer.
 *
 * @category PHP
 * @package Marshaller
 * @subpackage Tests
 * @author Matthias Molitor <matthias@matthimatiker.de>
 * @copyright 2012 Matthias Molitor
 * @license http://www.opensource.org/licenses/BSD-3-Clause BSD License
 * @link https://github.com/Matthimatiker/PackageMarshaller
 * @since 10.11.2012
 */
class InstallerTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * System under test.
     *
     * @var \Composer\Installer\InstallerInterface
     */
    protected $installer = null;
    
    /**
     * The composer instance that is injected into the installer.
     *
     * @var \Composer\Composer
     */
    protected $composer = null;
    
    /**
     * IO instance that is injected into the installer.
     *
     * @var \Composer\IO\IOInterface
     */
    protected $io = null;
    
    /**
     * See {@link PHPUnit_Framework_TestCase::setUp()} for details.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->composer  = $this->createComposer();
        $this->io        = $this->getMock('Composer\IO\IOInterface');
        $this->installer = new Installer($this->io, $this->composer);
    }
    
    /**
     * See {@link PHPUnit_Framework_TestCase::tearDown()} for details.
     */
    protected function tearDown()
    {
        $this->installer = null;
        $this->io        = null;
        $this->composer  = null;
        parent::tearDown();
    }
    
    /**
     * Checks if getInstallPath() returns at least a string.
     */
    public function testGetInstallPathReturnsString()
    {
        $path = $this->installer->getInstallPath($this->createPackage('installer/test'));
        $this->assertInternalType('string', $path);
    }
    
    /**
     * Creates a composer instance for testing.
     *
     * @return \Composer\Composer
     */
    protected function createComposer()
    {
        return new \Composer\Composer();
    }
    
    /**
     * Creates an IO instance for testing.
     *
     * @return \Composer\IO\IOInterface
     */
    protected function createIO()
    {
        return $this->getMock('Composer\IO\IOInterface');
    }
    
    /**
     * Creates a package for testing.
     *
     * @param string $prettyName The package name.
     * @return \Composer\Package\PackageInterface
     */
    protected function createPackage($prettyName, array $extra = array())
    {
        $package = $this->getMock('Composer\Package\PackageInterface');
        $package->expects($this->any())
                ->method('getPrettyName')
                ->will($this->returnValue($prettyName));
        $package->expects($this->any())
                ->method('getExtra')
                ->will($this->returnValue($extra));
        return $package;
    }
    
}
