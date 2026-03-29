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
use function \Kado\substring;


/**
 * The VFS VfsHandler.
 *
 * It maps a single Folder to a virtual file system, identified by a protocol.
 */
class VfsHandler implements IVfsHandler
{


    #region // – – –   P R I V A T E   F I E L D S   – – – – – – – – – – – – – – – – – – – – – – – –

    /** @type string The root folder mapped by the protocol. */
    private string $_rootFolder = '';

    /** @type string The protocol name. */
    private string $_protocolName = '';

    /** @type string The protocol separator. */
    private string $_protocolSeparator = '';

    /** @type array Optional replacements. */
    private array $_replacements = [];

    #endregion


    #region // – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –

    /**
     * Initialize a new \Kado\IO\Vfs\VfsHandler instance.
     *
     * @param string $name The VfsHandler name (only required for identification).
     * @param string $protocolName The VFS protocol name.
     * @param string $protocolSeparator The VFS protocol separator.
     * @param string $rootFolder The VFS root folder (directory). The used protocol points to this folder.
     * @param string[] $replacements The optional replacements. Replaces a part of a path with format ${replacementName}
     * @throws ArgumentException
     */
    public function __construct(
        private readonly string $name, string $protocolName, string $protocolSeparator, string $rootFolder,
        array $replacements = [] )
    {

        if ( ! empty( $protocolName ) && ! empty( $protocolSeparator ) )
        {
            $this->setProtocol( $protocolName, $protocolSeparator );
        }

        if ( ! empty( $rootFolder ) )
        {
            $this->setRootFolder( $rootFolder );
        }

        if ( 0 < \count( $replacements ) )
        {
            $this->addReplacements( $replacements );
        }

    }

    #endregion


    #region // – – –   P U B L I C   M E T H O D S   – – – – – – – – – – – – – – – – – – – – – – – –

    /**
     * Sets the VFS protocol name and separator.
     *
     * @param string $name
     * @param string $separator
     * @return self
     */
    public function setProtocol( string $name, string $separator = '://' ) : self
    {

        $this->_protocolName = ( '' === \trim( $name ) ) ? '' : $name;
        $this->_protocolSeparator = ( '' === \trim( $separator ) ) ? '' : $separator;

        return $this;

    }

    /**
     * Sets the VFS protocol name.
     *
     * @param string $name
     * @return self
     */
    public function setProtocolName( string $name ) : self
    {

        $this->_protocolName = ( '' === \trim( $name ) ) ? '' : $name;

        return $this;

    }

    /**
     * Sets the VFS protocol separator.
     *
     * @param string $separator
     * @return self
     */
    public function setProtocolSeparator( string $separator = '://' ) : self
    {

        $this->_protocolSeparator =  ( '' === \trim( $separator ) ) ? '' : $separator;

        return $this;

    }

    /**
     * Sets the VFS root folder (directory). The used protocol points to this folder.
     *
     * @param string $folder
     * @return self
     * @throws ArgumentException If the folder not exists
     */
    public function setRootFolder( string $folder ) : self
    {

        if ( ! @\is_dir( $folder ) )
        {
            throw new ArgumentException(
                'folder',
                $folder,
                'The defined VFS root directory not exists!'
            );
        }

        $this->_rootFolder = \rtrim( $folder, '/\\' );

        return $this;

    }

    /**
     * Gets the handler name
     *
     * @return string
     */
    public function getName() : string
    {

        return $this->name;

    }

    /**
     * Gets the protocol (name + separator)
     *
     * @return string
     */
    public function getProtocol() : string
    {

        return $this->_protocolName . $this->_protocolSeparator;

    }

    /**
     * Gets the protocol name
     *
     * @return string
     */
    public function getProtocolName() : string
    {

        return $this->_protocolName;

    }

    /**
     * Gets the protocol separator
     *
     * @return string
     */
    public function getProtocolSeparator() : string
    {

        return $this->_protocolSeparator;

    }

    /**
     * Gets the VFS root folder.
     *
     * @return string
     */
    public function getRootFolder() : string
    {

        return $this->_rootFolder;

    }

    /**
     * Gets if a valid, usable protocol is defined.
     *
     * @return bool
     */
    public function isValid() : bool
    {

        return '' !== $this->getProtocol();

    }

    /**
     * Add or set a replacement.
     *
     * It replaces a part of a path with format ${replacementName}
     *
     * @param  string      $name  The name of the replacement
     * @param  string|null $value The replacement string value (or NULL to remove a replacement)
     * @return self
     */
    public function addReplacement( string $name, ?string $value ) : self
    {

        if ( null === $value )
        {

            unset( $this->_replacements[ $name ] );

            return $this;

        }

        $this->_replacements[ $name ] = $value;

        return $this;

    }

    /**
     * Add or set one or more replacements.
     *
     * It replaces a part of a path with format ${replacementName}
     *
     * @param array $replacements Associative array with replacements (keys are the names)
     * @return self
     */
    public function addReplacements( array $replacements ) : self
    {

        foreach ( $replacements as $name => $value )
        {

            if ( null === $value )
            {
                unset( $this->_replacements[ $name ] );
                continue;
            }

            $this->_replacements[ $name ] = $value;

        }

        return $this;

    }

    /**
     * Checks if a replacement with defined name exists.
     *
     * @param string $name
     * @return bool
     */
    public function hasReplacement( string $name ) : bool
    {

        return isset( $this->_replacements[ $name ] );

    }

    /**
     * Tries to parse a path, using a VFS protocol and replaces the protocol with a path
     *
     * @param string $pathRef
     * @param array  $dynamicReplacements
     * @return bool Return TRUE on success or false otherwise.
     */
    public function tryParse( string &$pathRef, array $dynamicReplacements = [] ) : bool
    {

        $protocol = $this->getProtocol();

        if ( '' === $protocol || ! \str_starts_with( $pathRef, $protocol ) )
        {
            return false;
        }

        if ( \count( $dynamicReplacements ) > 0 )
        {
            $this->addReplacements( $dynamicReplacements );
        }

        $pathRef = $this->_rootFolder . DIRECTORY_SEPARATOR . substring( $pathRef, \mb_strlen( $protocol ) );

        $pathRef = \preg_replace_callback(
            '~\\${([A-Za-z0-9_.-]+)}~',
            function ( $matches )
            {

                if ( ! isset( $this->_replacements[ $matches[ 1 ] ] ) )
                {
                    return $matches[ 0 ];
                }

                return $this->_replacements[ $matches[ 1 ] ];
            },
            $pathRef
        );

        return true;

    }

    #endregion


    #region // – – –   P U B L I C   S T A T I C   M E T H O D S   – – – – – – – – – – – – – – – – –

    /**
     * @param string $name
     * @param string $protocolName
     * @param string $protocolSeparator
     * @param string $rootFolder
     * @param array $replacements
     * @return VfsHandler
     * @throws ArgumentException
     */
    public static function Create(
        string $name, string $protocolName, string $protocolSeparator, string $rootFolder, array $replacements = [] )
        : VfsHandler
    {

        return new self( $name, $protocolName, $protocolSeparator, $rootFolder, $replacements );

    }

    #endregion


}

