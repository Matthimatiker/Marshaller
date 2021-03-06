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
     * Ensures that the installer supports packages of type "marshaller-asset".
     */
    public function testInstallerSupportsMarshallerAssets()
    {
        $this->assertTrue($this->installer->supports('marshaller-asset'));
    }
    
    /**
     * Ensures that the installer does not support packages of types
     * that it should not be responsible for.
     */
    public function testInstallerDoesNotSupportPackagesOfUnknownTypes()
    {
        $this->assertFalse($this->installer->supports('other-type'));
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
     * Checks if getInstallPath() returns the path that is configured
     * by the installed package.
     */
    public function testGetInstallPathReturnsPathFromInstalledPackage()
    {
        $configuredPath = 'public/asset';
        $package        = $this->createPackage('installer/test', array('install-path' => $configuredPath));
        $this->assertEquals($configuredPath, $this->installer->getInstallPath($package));
    }
    
    /**
     * Ensures that the installer reads the installation path from the "installation-path"
     * attribute of the installed package as described in the documentation.
     *
     * @see https://github.com/Matthimatiker/Marshaller/issues/1
     */
    public function testGetInstallPathReturnsPathFromInstallationPathOfInstalledPackage()
    {
        $configuredPath = 'public/asset';
        $package        = $this->createPackage('installer/test', array('installation-path' => $configuredPath));
        $this->assertEquals($configuredPath, $this->installer->getInstallPath($package));
    }
    
    /**
     * Ensures that getInstallPath() returns the default path in the vendor
     * directory if no more specific path configuration is found.
     */
    public function testGetInstallPathReturnsDefaultPathIfNoInformationIsAvailable()
    {
        $package  = $this->createPackage('installer/test');
        $expected = $this->normalizePath($this->getVendorDir() . '/installer/test');
        $this->assertEquals($expected, $this->normalizePath($this->installer->getInstallPath($package)));
    }
    
    /**
     * Ensures that the installer uses the package path that is configured in the
     * root package, if available.
     */
    public function testGetInstallPathReturnsConfiguredPathFromRootPackageIfAvailable()
    {
        $options = array(
            'installer-paths' => array(
                'installer/test' => 'another/path'
            )
        );
        $rootPackage = $this->createRootPackage($options);
        $this->composer->setPackage($rootPackage);
        
        $package = $this->createPackage('installer/test', array('install-path' => 'public/asset'));
        $this->assertEquals('another/path', $this->installer->getInstallPath($package));
    }
    
    /**
     * Ensures that the installer reads the installation path from the "installation-paths"
     * attribute of the root package as described in the documentation.
     *
     * @see https://github.com/Matthimatiker/Marshaller/issues/1
     */
    public function testGetInstallPathReturnsPathFromInstallationPathsOfRootPackageIfAvailable()
    {
        $options = array(
            'installation-paths' => array(
                'installer/test' => 'another/path'
            )
        );
        $rootPackage = $this->createRootPackage($options);
        $this->composer->setPackage($rootPackage);
        
        $package = $this->createPackage('installer/test', array('installation-path' => 'public/asset'));
        $this->assertEquals('another/path', $this->installer->getInstallPath($package));
    }
    
    /**
     * Ensures that the installer does not use paths for other packages, which are configured
     * in the root package.
     */
    public function testGetInstallPathDoesNotReturnPathFromUnrelatedPackageThatIsConfiguredInRootPackage()
    {
        $options = array(
            'installer-paths' => array(
                'unrelated/package' => 'another/path'
            )
        );
        $rootPackage = $this->createRootPackage($options);
        $this->composer->setPackage($rootPackage);
        
        $package = $this->createPackage('installer/test', array('install-path' => 'public/asset'));
        $this->assertNotEquals('another/path', $this->installer->getInstallPath($package));
    }
    
    /**
     * Creates a composer instance for testing.
     *
     * @return \Composer\Composer
     */
    protected function createComposer()
    {
        $config = new \Composer\Config();
        $config->merge(array(
            'config' => array(
                'vendor-dir' => dirname(__FILE__) . '/_files/vendor',
                'bin-dir'    => dirname(__FILE__) . '/_files/bin',
            )
        ));
        $composer = new \Composer\Composer();
        $composer->setConfig($config);
        $composer->setPackage($this->createRootPackage());
        return $composer;
    }
    
    /**
     * Returns the path to the configured vendor directory.
     *
     * @return string
     */
    protected function getVendorDir()
    {
        return $this->composer->getConfig()->get('vendor-dir');
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
     * Creates a root package with the name root/package.
     *
     * @param array(mixed) $extra Additional information.
     * @return \Composer\Package\RootPackageInterface
     */
    protected function createRootPackage($extra = array())
    {
        return $this->createPackage('root/package', $extra, 'Composer\Package\RootPackageInterface');
    }
    
    /**
     * Creates a package for testing.
     *
     * @param string $prettyName The package name.
     * @param array(mixed) $extra Additional configuration.
     * @param string $type The mocked type. Should implement the PackageInterface.
     * @return \Composer\Package\PackageInterface
     */
    protected function createPackage($prettyName, array $extra = array(), $type = 'Composer\Package\PackageInterface')
    {
        $package = $this->getMock($type);
        $package->expects($this->any())
                ->method('getPrettyName')
                ->will($this->returnValue($prettyName));
        $package->expects($this->any())
                ->method('getExtra')
                ->will($this->returnValue($extra));
        return $package;
    }
    
    /**
     * Normalizes the separators in the given path to "/".
     *
     * This ensures, that different paths are compareable, regardless
     * of the operating system.
     *
     * @param string $path
     * @return string The normalized path.
     */
    protected function normalizePath($path)
    {
        return str_replace(array('/', '\\'), '/', $path);
    }
    
}
