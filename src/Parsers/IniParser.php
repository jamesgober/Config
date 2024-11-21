<?php
/**
 * IniParser.php
 * 
 * Parses configuration data from INI files. Supports both simple and sectioned INI structures.
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

use \is_file;
use \sprintf;
use \is_readable;
use \parse_ini_file;
use \INI_SCANNER_TYPED;
use \JG\Config\Exceptions\ConfigParseException;

/**
 * IniParser
 * 
 * Parses configuration data from INI files, supporting both flat and 
 * sectioned structures. Sectioned INI files are returned as multidimensional arrays.
 * 
 * @package JG\Config\Parsers
 */
class IniParser implements ParserInterface
{
    /**
     * Parses an INI file and returns its contents as an associative array.
     *
     * This method uses PHP's `parse_ini_file()` function with support for
     * sectioned INI files (returned as nested arrays).
     *
     * Example INI file:
     * ```
     * [database]
     * host = localhost
     * port = 3306
     * 
     * [app]
     * debug = true
     * ```
     *
     * @param string $filePath The path to the INI file.
     * @return array The parsed contents of the INI file as an associative array.
     * @throws ConfigParseException If the INI file cannot be read or parsed.
     */
    public function parse(string $filePath): array
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new ConfigParseException(
                sprintf("INI file not found or unreadable: %s", $filePath)
            );
        }

        $data = parse_ini_file($filePath, true, INI_SCANNER_TYPED);

        if ($data === false) {
            throw new ConfigParseException(
                sprintf("Failed to parse INI configuration file: %s", $filePath)
            );
        }

        return $data;
    }
}