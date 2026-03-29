<?php
/**
 * @author       Sweet Andi
 * @copyright  © 2026-2021, Sweet Andi
 * @package      Kado
 * @since        2026-03-24
 * @version      1.0.0
 */


declare( strict_types = 1 );


namespace Kado\IO\Vfs;


use \Kado\ArgumentException;
use \Kado\IValidStatus;


/**
 * The VFS VfsHandler.
 *
 * It maps a single Folder to a virtual file system, identified by a protocol.
 */
interface IVfsHandler extends IValidStatus
{


    /**
     * Sets the VFS protocol name and separator.
     *
     * @param string $name
     * @param string $separator
     * @return self
     */
    public function setProtocol( string $name, string $separator = '://' ) : self;

    /**
     * Sets the VFS protocol name.
     *
     * @param string $name
     * @return self
     */
    public function setProtocolName( string $name ) : self;

    /**
     * Sets the VFS protocol separator.
     *
     * @param string $separator
     * @return self
     */
    public function setProtocolSeparator( string $separator = '://' ) : self;

    /**
     * Sets the VFS root folder (directory). The used protocol points to this folder.
     *
     * @param string $folder
     * @return self
     * @throws ArgumentException If the folder not exists
     */
    public function setRootFolder( string $folder ) : self;

    /**
     * Gets the handler name
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Gets the protocol (name + separator)
     *
     * @return string
     */
    public function getProtocol() : string;

    /**
     * Gets the protocol name
     *
     * @return string
     */
    public function getProtocolName() : string;

    /**
     * Gets the protocol separator
     *
     * @return string
     */
    public function getProtocolSeparator() : string;

    /**
     * Gets the VFS root folder.
     *
     * @return string
     */
    public function getRootFolder() : string;

    /**
     * Add or set a replacement.
     *
     * It replaces a part of a path with format ${replacementName}
     *
     * @param  string      $name  The name of the replacement
     * @param  string|null $value The replacement string value (or NULL to remove a replacement)
     * @return self
     */
    public function addReplacement( string $name, ?string $value ): self;

    /**
     * Add or set one or more replacements.
     *
     * It replaces a part of a path with format ${replacementName}
     *
     * @param array $replacements Associative array with replacements (keys are the names)
     * @return self
     */
    public function addReplacements( array $replacements ): self;

    /**
     * Checks if a replacement with defined name exists.
     *
     * @param string $name
     * @return bool
     */
    public function hasReplacement( string $name ) : bool;

    /**
     * Tries to parse a path, using a VFS protocol and replaces the protocol with a path
     *
     * @param string $pathRef
     * @param array  $dynamicReplacements
     * @return bool Return TRUE on success or false otherwise.
     */
    public function tryParse( string &$pathRef, array $dynamicReplacements = [] ) : bool;


}

