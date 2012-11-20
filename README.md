# Marshaller #

Marshaller is a composer installer that copies packages to a configurable location.

## Activate the installer ##

To activate the installer, a package must declare its type as *marshaller-asset*.
Additionally, the installer package *matthimatiker/marshaller* must be required:

    {
        "name": "my/package",
        "description": "Package that can be copied to a configured location.",
        "type": "marshaller-asset",
        "require": {
            "matthimatiker/marshaller": "*"
        }
    }

## Define default installation path ##

A package that uses the Marshaller installer may define its default installation path.
The path is added to the *extra* configuration. 

The following package uses *public/custom* as installation path:


    {
        "name": "my/package",
        "description": "Package that will be copied to public/custom.",
        "type": "marshaller-asset",
        "require": {
            "matthimatiker/marshaller": "*"
        },
        "extra": {
            "installation-path": "public/custom"
        }
    }

## Overwrite installation path in root package ##

The root package can overwrite the installation path of all dependencies that are
installed via Marshaller. 

To change the path, the new target is added to the *installation-paths* map,
which resides in the *extra* configuration section:

    {
        "name": "root/package",
        "description": "Root package that overwrites an installation path.",
        "type": "marshaller-asset",
        "require": {
            "matthimatiker/marshaller": "*"
        },
        "extra": {
            "installation-paths": {
                "my/package": "another/path"
            }
        }
    }
    
The example above enforces the installation of the package *my/package* into
the directory *another/path*.

If an required package as well as the root package define an installation
path, then the configuration in the root package will be used.

## Fallback behavior ##

If a package uses the Marshaller installer, but neither itself nor 
the root package define an installation path, then the default behavior
of Composer will take place and the package will be copied to the *vendor*
directory.