<?php
/**
 * @author       Sweet Andi
 * @copyright  © 2026-2021, Sweet Andi
 * @package      Kado
 * @since        2026-03-24
 * @version      1.0.0
 */


declare( strict_types=1 );


namespace Kado\IO\Vfs;


use Override;


/**
 * The VFS handler manager.
 */
class VfsManager implements IVfsManager
{


    #region // – – –   P R I V A T E   F I E L D S   – – – – – – – – – – – – – – – – – – – – – – – –

    /**
     * @var IVfsHandler[]
     */
    private array $_handlers;

    #endregion


    #region // – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –

    /**
     * Initialize a new \Kado\IO\Vfs\VfsManager instance.
     *
     * @param IVfsHandler|null $firstHandler Optional First assigned VFS VfsHandler
     */
    public function __construct( ?IVfsHandler $firstHandler = null )
    {

        $this->_handlers = [];

        if ( null !== $firstHandler )
        {
            $this->addHandler( $firstHandler );
        }

    }

    #endregion


    #region // – – –   P U B L I C   M E T H O D S   – – – – – – – – – – – – – – – – – – – – – – – –

    /**
     * Add/register one or more handlers.
     *
     * @param  IVfsHandler[] $handlers
     * @return self
     */
    #[Override] public function addHandlers( array $handlers ) : self
    {

        foreach ( $handlers as $handler )
        {
            if ( ! ( $handler instanceof VfsHandler ) ) { continue; }
            $this->_handlers[ $handler->getName() ] = $handler;
        }

        return $this;

    }

    /**
     * Add/register a handler.
     *
     * @param  IVfsHandler $handler
     * @return self
     */
    #[Override] public function addHandler(IVfsHandler $handler ) : self
    {

        $this->_handlers[ $handler->getName() ] = $handler;

        return $this;

    }

    /**
     * Gets the handler with defined name.
     *
     * @param  string $handlerName
     * @return IVfsHandler|null
     */
    #[Override] public function getHandler(string $handlerName ) : ?IVfsHandler
    {

        return $this->_handlers[ $handlerName ] ?? null;

    }

    /**
     * Get all handlers as associative array.
     *
     * Keys are the handler names, values are the VfsHandler instances.
     *
     * @return IVfsHandler[]
     */
    #[Override] public function getHandlers() : array
    {

        return $this->_handlers;

    }

    /**
     * Gets if the handler is defined.
     *
     * @param string|IVfsHandler $handler VfsHandler or handler name.
     *
     * @return bool
     */
    #[Override] public function hasHandler( string|IVfsHandler $handler ) : bool
    {

        if ( $handler instanceof IVfsHandler )
        {
            return isset( $this->_handlers[ $handler->getName() ] );
        }

        if ( ! \is_string( $handler ) )
        {
            return false;
        }

        return isset( $this->_handlers[ $handler ] );

    }

    /**
     * Deletes all current defined handlers.
     */
    #[Override] public function clearHandlers() : void
    {

        $this->_handlers = [];

    }

    /**
     * Gets the names of all defined handlers
     *
     * @return array
     */
    #[Override] public function getHandlerNames() : array
    {

        return \array_keys( $this->_handlers );

    }

    /**
     * If a VFS handler matches the defined protocol, the $path is parsed (it means the protocol and known replacements
     * are replaces by associated path parts.
     *
     * @param  string $path
     * @param  array  $dynamicReplacements
     * @return string Returns the parsed path
     */
    #[Override] public function parsePath(string $path, array $dynamicReplacements = [] ) : string
    {

        foreach ( $this->_handlers as $handler )
        {
            if ( $handler->tryParse( $path, $dynamicReplacements ) ) { break; }
        }

        return $path;

    }

    #endregion


    #region // – – –   P U B L I C   S T A T I C   M E T H O D S   – – – – – – – – – – – – – – – – –

    /**
     * The static constructor for fluent usage.
     *
     * @return IVfsManager
     */
    public static function Create() : IVfsManager
    {

        return new self();

    }

    #endregion


}

