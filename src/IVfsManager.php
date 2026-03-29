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


/**
 * The VFS handler manager interface.
 */
interface IVfsManager
{

    /**
     * Add/register one or more handlers.
     *
     * @param  IVfsHandler[] $handlers
     * @return self
     */
    public function addHandlers( array $handlers ): self;

    /**
     * Add/register a handler.
     *
     * @param  IVfsHandler $handler
     * @return self
     */
    public function addHandler( IVfsHandler $handler ): self;

    /**
     * Gets the handler with defined name.
     *
     * @param  string $handlerName
     * @return IVfsHandler|null
     */
    public function getHandler( string $handlerName ) : ?IVfsHandler;

    /**
     * Get all handlers as associative array.
     *
     * Keys are the handler names, values are the VfsHandler instances.
     *
     * @return IVfsHandler[]
     */
    public function getHandlers() : array;

    /**
     * Gets if the handler is defined.
     *
     * @param string|IVfsHandler $handler VfsHandler or handler name.
     *
     * @return bool
     */
    public function hasHandler( string|IVfsHandler $handler ) : bool;

    /**
     * Deletes all current defined handlers.
     */
    public function clearHandlers() : void;

    /**
     * Gets the names of all defined handlers
     *
     * @return array
     */
    public function getHandlerNames() : array;

    /**
     * If a VFS handler matches the defined protocol, the $path is parsed (it means the protocol and known replacements
     * are replaces by associated path parts.
     *
     * @param  string $path
     * @param  array  $dynamicReplacements
     * @return string Returns the parsed path
     */
    public function parsePath( string $path, array $dynamicReplacements = [] ) : string;

}
