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
      * See {@link Composer\Installer\InstallerInterface::getInstallPath()} for details.
      *
      * @param PackageInterface $package The package that will be installed.
      * @return string The installation path.
      */
     public function getInstallPath(PackageInterface $package)
     {
         $config = $package->getExtra();
         if (isset($config['install-path'])) {
             return $config['install-path'];
         }
         return parent::getInstallPath($package);
     }
     
 }
 