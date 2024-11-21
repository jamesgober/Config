<?php
/**
 * InvalidParserException.php
 * 
 * Custom exception for invalid configuration parser operations.
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

use \InvalidArgumentException;

/**
 * InvalidParserException
 * 
 * Thrown when an invalid parser is registered, accessed, or used within
 * the configuration management system.
 * 
 * @package JG\Config\Exceptions
 */
class InvalidParserException extends InvalidArgumentException
{
    /**
     * Constructs a new InvalidParserException.
     * 
     * @param string $message The exception message.
     * @param int $code The exception code (default: 0).
     * @param \Throwable|null $previous A previous exception for chaining (default: null).
     */
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Provides a string representation of the exception.
     * 
     * @return string A string containing exception details.
     */
    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}