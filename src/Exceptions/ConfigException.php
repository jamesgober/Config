<?php
/**
 * ConfigException.php
 * 
 * A custom exception class for handling errors specific to configuration
 * operations within the Config library.
 * 
 * PHP version 8.2
 *
 * @package    JG\Config\Exceptions
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <me@jamesgober.com>
 * @link       https://github.com/jamesgober/Config
 * @license    MIT License
 * @copyright  2024 James Gober (https://jamesgober.com)
 */
declare(strict_types=1);

namespace JG\Config\Exceptions;

use \Exception;

/**
 * ConfigException
 * 
 * Represents errors that occur during configuration operations within
 * the Config library. This exception is designed to encapsulate all
 * configuration-specific error scenarios, providing clear messaging
 * and context for debugging.
 * 
 * @package JG\Config\Exceptions
 */
class ConfigException extends Exception
{
    /**
     * Constructs a new ConfigException instance.
     * 
     * @param string $message The detailed exception message.
     * @param int $code The exception code (default: 0).
     * @param \Throwable|null $previous A previous exception used for chaining (default: null).
     */
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns a string representation of the exception.
     * 
     * @return string A string containing the exception details.
     */
    public function __toString(): string
    {
        return sprintf(
            "%s: [Code %d]: %s",
            __CLASS__,
            $this->code,
            $this->message
        );
    }
}