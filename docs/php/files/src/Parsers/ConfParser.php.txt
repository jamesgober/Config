<?php
/**
 * ConfParser.php
 * 
 * Parses key-value pairs from custom CONF configuration files. These files
 * typically use `:` or `=` as separators for key-value pairs.
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

use \trim;
use \sprintf;
use \is_numeric;
use \strtolower;
use \preg_match_all;
use \file_get_contents;
use \JG\Config\Exceptions\ConfigParseException;

/**
 * ConfParser
 * 
 * Parses key-value pairs from CONF configuration files. This parser supports
 * flexible key-value syntax with either `:` or `=` as separators.
 * 
 * @package JG\Config\Parsers
 */
class ConfParser implements ParserInterface
{
    /**
     * Parses a CONF configuration file into an associative array.
     *
     * The method reads key-value pairs from the file, where keys and values are separated
     * by either `:` or `=`. Leading and trailing whitespace is trimmed for both keys and values.
     *
     * Example:
     * ```
     * host: localhost
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

        // Attempt to retrieve the file contents
        $data = file_get_contents($filePath);

        if ($data === false) {
            throw new ConfigParseException(
                sprintf("Failed to read CONF configuration file: %s", $filePath)
            );
        }

        $out = [];

        // Define regex for capturing key-value pairs with `:` or `=` as separator
        $regex = '/^(?P<key>[a-zA-Z._\-]+[a-zA-Z0-9]*)\s*[:=]\s*(?P<value>.*)$/m';

        // Process matches using the defined regex pattern
        if (preg_match_all($regex, $data, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $key = trim($match['key']);
                $value = trim($match['value']);

                // Convert scalar values (e.g., true, false, null, numbers) if applicable
                $value = $this->convertValue($value);

                if ($key !== '' && $value !== '') {
                    $out[$key] = $value;
                }
            }
        } else {
            throw new ConfigParseException(
                sprintf("No valid key-value pairs found in CONF file: %s", $filePath)
            );
        }

        return $out;
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