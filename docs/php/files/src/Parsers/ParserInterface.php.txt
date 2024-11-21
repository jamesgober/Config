<?php
/**
 * ParserInterface.php
 * 
 * Interface for configuration file parsers.
 * Provides a standard contract for implementing parsers for different 
 * configuration file types such as JSON, PHP, INI, and others.
 * 
 * PHP version 8.2
 *
 * @package    JG\Config\Parsers
 * @version    1.0.0
 * @author     James Gober <code@jamesgober.com>
 * @link       https://github.com/jamesgober/Config
 * @license    MIT License
 * @copyright  2024 James Gober (https://jamesgober.com)
 */
declare(strict_types=1);

namespace JG\Config\Parsers;

/**
 * ParserInterface
 * 
 * Defines a contract for configuration file parsers. Each implementing 
 * parser class is responsible for parsing a specific configuration 
 * file type (e.g., JSON, PHP, INI) and returning the parsed data as an 
 * associative array.
 * 
 * @package JG\Config\Parsers
 */
interface ParserInterface
{
    /**
     * Parses a configuration file and returns its contents as an associative array.
     *
     * @param string $filePath The path to the configuration file.
     * @return array An associative array representing the parsed configuration data.
     * @throws \Config\Exceptions\ConfigException If the file cannot be parsed.
     */
    public function parse(string $filePath): array;
}