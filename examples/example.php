<?php

include \dirname( __DIR__ ) . '/vendor/autoload.php';

use \Kado\IO\Vfs\VfsManager;
use \Kado\IO\Vfs\VfsHandler;

$vfsManager = VfsManager::Create()->addHandler(
    VfsHandler::Create( 'Test 1', 'foo', ':/', __DIR__ )
    ->addReplacement( 'myReplacement', 'Blub' )
);


echo '__DIR__: ', __DIR__, "\n";
echo $vfsManager->parsePath( 'foo:/bar/bazz.txt' ), "\n";
echo $vfsManager->parsePath( 'foo:/${myDynamicReplacement}/bazz.txt', [ 'myDynamicReplacement' => 'abc/def' ] ), "\n";
echo $vfsManager->parsePath( 'foo:/Bar/Bazz/${myReplacement}.xml' );
