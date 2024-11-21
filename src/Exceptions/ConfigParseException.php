<?php
/**
 * ConfigParseException.php
 * 
 * Exception for handling parsing-related errors in configuration files.
 * 
 * PHP version 8.2
 *
 * @package    JG\Config\Exceptions
 * @version    1.0.0
 * @author     James Gober <code@jamesgober.com>
 * @link       https://github.com/jamesgober/Config
 * @license    MIT License
 * @copyright  2024 James Gober (https://jamesgober.com)
 */
declare(strict_types=1);

namespace JG\Config\Exceptions;

use \Exception;

/**
 * ConfigParseException
 * 
 * Thrown when a configuration file fails to parse properly.
 * 
 * @package JG\Config\Exceptions
 */
class ConfigParseException extends Exception
{
    /**
     * Constructs a new ConfigParseException instance.
     * 
     * @param string $message The error message.
     * @param int $code The error code (default: 0).
     * @param \Throwable|null $previous Optional previous exception for chaining.
     */
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Provides a string representation of the exception.
     * 
     * @return string Exception details as a string.
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