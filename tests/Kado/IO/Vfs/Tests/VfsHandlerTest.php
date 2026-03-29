<?php

namespace Kado\IO\Vfs\Tests;


use Kado\ArgumentException;
use Kado\IO\Vfs\VfsHandler;
use PHPUnit\Framework\TestCase;


class VfsHandlerTest extends TestCase
{


    function testInit()
    {

        $h = new VfsHandler( 'Foo VfsHandler', 'foo', '://', __DIR__, [ 'blub' => 14 ] );
        $this->assertInstanceOf( VfsHandler::class, $h );
        $this->assertSame( 'Foo VfsHandler', $h->getName() );
        $this->assertSame( 'foo://', $h->getProtocol() );
        $this->assertSame( 'foo', $h->getProtocolName() );
        $this->assertSame( '://', $h->getProtocolSeparator() );
        $this->assertTrue( $h->hasReplacement( 'blub' ) );
        $this->assertFalse( $h->hasReplacement( 'blubb' ) );
        $this->assertSame( __DIR__, $h->getRootFolder() );

    }

    function testSetProtocolName()
    {

        $h = new VfsHandler( 'Foo VfsHandler', 'foo', '://', __DIR__ );
        $this->assertInstanceOf( VfsHandler::class, $h->setProtocolName( 'bar' ) );
        $this->assertSame( 'bar', $h->getProtocolName() );

    }
    function testSetProtocolSeparator()
    {

        $h = new VfsHandler( 'Foo VfsHandler', 'foo', '://', __DIR__ );
        $this->assertInstanceOf( VfsHandler::class, $h->setProtocolSeparator( ':/' ) );
        $this->assertSame( ':/', $h->getProtocolSeparator() );

    }
    function testSetRootFolderException()
    {

        $this->expectException( ArgumentException::class );
        $h = new VfsHandler( 'Foo VfsHandler', 'foo', '://', __DIR__ );
        $h->setRootFolder( __DIR__ . '/foobarbaz' );

    }
    function testIsValid()
    {

        $h = new VfsHandler( 'Foo VfsHandler', 'foo', '://', __DIR__ );
        $this->assertTrue( $h->isValid() );
        $h = new VfsHandler( 'Foo VfsHandler', '', '://', __DIR__ );
        $this->assertFalse( $h->isValid() );

    }
    function testAddReplacement()
    {

        $h = new VfsHandler( 'Foo VfsHandler', 'foo', '://', __DIR__ );
        $this->assertFalse( $h->hasReplacement( 'blub' ) );
        $this->assertSame( $h, $h->addReplacement( 'blub', '1234' ) );
        $this->assertTrue( $h->hasReplacement( 'blub' ) );
        $this->assertSame( $h, $h->addReplacement( 'blub', null ) );
        $this->assertFalse( $h->hasReplacement( 'blub' ) );

    }
    function testAddReplacements()
    {

        $h = new VfsHandler( 'Foo VfsHandler', 'foo', '://', __DIR__, [ 'blub' => 14 ] );
        $this->assertTrue( $h->hasReplacement( 'blub' ) );
        $h->addReplacements( [ 'blub' => null, 'blubber' => '1212' ] );
        $this->assertFalse( $h->hasReplacement( 'blub' ) );
        $this->assertTrue( $h->hasReplacement( 'blubber' ) );

    }
    function testTryParse()
    {

        $h = VfsHandler::Create( 'Foo VfsHandler', 'foo', '://', __DIR__, [ 'blub' => 14 ] );
        $path = 'foo://bar/baz/${xyz}/${abc}';
        $this->assertTrue( $h->tryParse( $path, [ 'xyz' => '123' ] ) );
        $this->assertSame( __DIR__ .  DIRECTORY_SEPARATOR . 'bar/baz/123/${abc}', $path );
        $path = 'blub://bar/baz';
        $this->assertFalse( $h->tryParse( $path ) );

    }
    #function test() { $this->assertSame( '', '' ); }
    #function test() { $this->assertSame( '', '' ); }
    #function test() { $this->assertSame( '', '' ); }


}
