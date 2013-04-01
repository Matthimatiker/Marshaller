<?php

/**
 * \Marshaller\Installer
 *
 * @category PHP
 * @package Marshaller
 * @author Matthias Molitor <matthias@matthimatiker.de>
 * @copyright 2012 Matthias Molitor
 * @license http://www.opensource.org/licenses/BSD-3-Clause BSD License
 * @link https://github.com/Matthimatiker/PackageMarshaller
 * @since 10.11.2012
 */

namespace Marshaller;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

/**
 * Installer that copies packages to a custom location.
 *
 * @category PHP
 * @package Marshaller
 * @author Matthias Molitor <matthias@matthimatiker.de>
 * @copyright 2012 Matthias Molitor
 * @license http://www.opensource.org/licenses/BSD-3-Clause BSD License
 * @link https://github.com/Matthimatiker/PackageMarshaller
 * @since 10.11.2012
 */
 class Installer extends LibraryInstaller
 {
     
     /**
      * Redeclared variable from library installer to provide
      * a better type hint.
      *
      * @var \Composer\Composer
      */
     protected $composer = null;
     
     /**
      * See {@link Composer\Installer\InstallerInterface::supports()} for details.
      *
      * @param string $packageType The type of the package that shall be installed.
      * @return boolean True if the installer is responsible for that package type, false otherwise.
      */
     public function supports($packageType)
     {
          return $packageType === 'marshaller-asset';
     }
     
     /**
      * See {@link Composer\Installer\InstallerInterface::getInstallPath()} for details.
      *
      * @param PackageInterface $package The package that will be installed.
      * @return string The installation path.
      */
     public function getInstallPath(PackageInterface $package)
     {
         // Try to read installation path from root package configuration.
         $rootPackageConfig = $this->composer->getPackage()->getExtra();
          if (isset($rootPackageConfig['installation-paths'][$package->getPrettyName()])) {
             return $rootPackageConfig['installation-paths'][$package->getPrettyName()];
         }
         // The attribute "installer-paths" is check to guarantee backwards compatibility
         // with version 0.1.1.
         if (isset($rootPackageConfig['installer-paths'][$package->getPrettyName()])) {
             return $rootPackageConfig['installer-paths'][$package->getPrettyName()];
         }
         
         // Try to read installation path from package configuration.
         $packageConfig = $package->getExtra();
         if (isset($packageConfig['installation-path'])) {
             return $packageConfig['installation-path'];
         }
         // The attribute "install-path" is check to guarantee backwards compatibility
         // with version 0.1.1.
         if (isset($packageConfig['install-path'])) {
             return $packageConfig['install-path'];
         }
         
         // No path configured, use default behavior as fallback.
         return parent::getInstallPath($package);
     }
     
 }
 