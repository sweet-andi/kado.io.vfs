# Kado.IO.VFS

## Virtual file system

This library gives you a simple virtual file system.


## Installation

inside the `composer.json`:

```json
{
    "require": {
        "php": ">=8.3",
        "kado/kado.io.vfs": "~1.0"
    }
}
```

## Usage

The usage is very easy.

```php
<?php
include \dirname( __DIR__ ) . '/vendor/autoload.php';

use \Kado\IO\Vfs\VfsManager;
use \Kado\IO\Vfs\VfsHandler;

$vfsManager = VfsManager::Create()
   ->addHandler(
      VfsHandler::Create( 'Test 1', 'foo', ':/', __DIR__ )
         ->addReplacement( 'myReplacement', 'Blub' )
   );


echo $vfsManager->parsePath( 'foo:/bar/bazz.txt' ), "\n";
echo $vfsManager->parsePath( 'foo:/${myDynamicPart}/bazz.txt', [ 'myDynamicReplacement' => 'abc/def' ] ), "\n";
echo $vfsManager->parsePath( 'foo:/Bar/Bazz/${myReplacement}.xml' );
```
