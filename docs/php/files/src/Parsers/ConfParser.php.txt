<?php
/**
 * ConfParser.php
 * 
 * Parses key-value pairs from custom CONF configuration files. These files
 * typically use `=` as the separator for key-value pairs.
 * 
 * PHP version 8.2
 *
 * @package    JG\Config\Parsers
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <me@jamesgober.com>
 * @link       https://github.com/jamesgober/Config
 * @license    MIT License
 * @copyright  2024 James Gober (https://jamesgober.com)
 */
declare(strict_types=1);

namespace JG\Config\Parsers;

use \file;
use \trim;
use \explode;
use \is_file;
use \preg_match;
use \is_readable;
use \JG\Config\Exceptions\ConfigParseException;

/**
 * ConfParser
 * 
 * Parses key-value pairs from CONF configuration files. This parser supports
 * clean formatting with `=` as the separator. It skips comments and empty lines
 * and trims whitespace while preserving inner spaces.
 * 
 * @package JG\Config\Parsers
 */
class ConfParser implements ParserInterface
{
    /**
     * Parses a CONF configuration file into an associative array.
     *
     * Example:
     * ```
     * host = localhost
     * user = root
     * ```
     *
     * @param string $filePath Path to the CONF file.
     * @return array Parsed configuration data as an associative array.
     * @throws ConfigParseException If the file cannot be read or parsed.
     */
    public function parse(string $filePath): array
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new ConfigParseException("File not found or unreadable: {$filePath}");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            throw new ConfigParseException("Failed to read the CONF file: {$filePath}");
        }

        $output = [];

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments
            if (str_starts_with($line, '#') || str_starts_with($line, ';')) {
                continue;
            }

            // Parse key=value pairs
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);

                // Convert scalar values if applicable
                $output[$key] = $this->convertValue($value);
            }
        }

        return $output;
    }

    /**
     * Converts string values to their respective PHP scalar types where applicable.
     *
     * - `true` and `false` become boolean.
     * - `null` becomes null.
     * - Numeric strings are cast to integers or floats.
     *
     * @param string $value The value to convert.
     * @return mixed Converted value.
     */
    private function convertValue(string $value): mixed
    {
        $lowerValue = strtolower($value);

        return match (true) {
            $lowerValue === 'true'  => true,
            $lowerValue === 'false' => false,
            $lowerValue === 'null'  => null,
            is_numeric($value)      => $value + 0, // Converts to int or float
            default                 => $value,
        };
    }
}