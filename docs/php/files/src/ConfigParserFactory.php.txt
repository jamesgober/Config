<?php
/**
 * ConfigParserFactory.php
 * 
 * Factory class for creating and managing configuration parsers based on file extensions.
 * Allows registration of custom parsers and overrides for default parsers.
 * 
 * PHP version 8.2
 *
 * @package    JG\Config
 * @version    1.0.0
 * @author     James Gober <code@jamesgober.com>
 * @link       https://github.com/jamesgober/Config
 * @license    MIT License
 * @copyright  2024 James Gober (https://jamesgober.com)
 */
declare(strict_types=1);

namespace JG\Config;

use \is_a;
use \ltrim;
use \pathinfo;
use \strtolower;
use \PATHINFO_EXTENSION;
use JG\Config\Parsers\ParserInterface;
use JG\Config\Exceptions\InvalidParserException;

/**
 * ConfigParserFactory
 * 
 * Factory class for creating and managing configuration parsers. Supports default parsers
 * for common file formats such as JSON, PHP, and XML, and allows custom parsers to be registered.
 * 
 * @package JG\Config
 */
class ConfigParserFactory
{
    /**
     * Default mapping of file extensions to parser class names.
     *
     * @var array<string, class-string<ParserInterface>>
     */
    private static array $configParsers = [
        'conf' => \JG\Config\Parsers\ConfParser::class,
        'ini'  => \JG\Config\Parsers\IniParser::class,
        'json' => \JG\Config\Parsers\JsonParser::class,
        'php'  => \JG\Config\Parsers\PhpParser::class,
        'xml'  => \JG\Config\Parsers\XmlParser::class,
        'yaml' => \JG\Config\Parsers\YamlParser::class,
        'yml'  => \JG\Config\Parsers\YamlParser::class,
    ];

    /**
     * Creates a parser instance based on the file's extension.
     *
     * @param string $filePath The path to the configuration file.
     * @return ParserInterface Returns an instance of the appropriate parser.
     * @throws InvalidParserException If no suitable parser is found.
     */
    public static function createParser(string $filePath): ParserInterface
    {
        // Extract and normalize file extension to lowercase
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // Retrieve the parser class for the extension
        $parserClass = self::$configParsers[$extension] ?? null;

        // Validate and instantiate the parser class
        if ($parserClass && is_a($parserClass, ParserInterface::class, true)) {
            return new $parserClass();
        }

        throw new InvalidParserException(
            "No suitable parser found for file extension: .$extension"
        );
    }

    /**
     * Registers a custom parser, allowing users to extend or override default parsers.
     *
     * @param string $extension The file extension for the parser (e.g., 'yaml').
     * @param class-string<ParserInterface> $parserClass Fully qualified class name of the parser,
     *                                                         must implement ParserInterface.
     * @return void
     * @throws InvalidParserException If the class does not implement the required interface.
     */
    public static function registerParser(string $extension, string $parserClass): void
    {
        // Validate that the class implements ParserInterface
        if (!is_a($parserClass, ParserInterface::class, true)) {
            throw new InvalidParserException(
                "Parser class '{$parserClass}' must implement ParserInterface."
            );
        }

        // Sanitize the extension and register the parser
        $extension = strtolower(ltrim($extension, '.'));
        self::$configParsers[$extension] = $parserClass;
    }

    /**
     * Bulk registers parsers from an associative array.
     *
     * @param array<string, class-string<ParserInterface>> $parsers
     *        An array mapping file extensions to parser class names.
     * @return void
     * @throws InvalidParserException If any parser class is invalid.
     */
    public static function loadParsers(array $parsers): void
    {
        foreach ($parsers as $extension => $parserClass) {
            self::registerParser($extension, $parserClass);
        }
    }

    /**
     * Retrieves the list of all registered parsers.
     *
     * @return array<string, class-string<ParserInterface>> The registered parsers.
     */
    public static function getParsers(): array
    {
        return self::$configParsers;
    }

    /**
     * Unregisters a parser for a specific file extension.
     *
     * @param string $extension The file extension to unregister (e.g., 'yaml').
     * @return bool True if the parser was successfully removed; false otherwise.
     */
    public static function unregisterParser(string $extension): bool
    {
        $extension = strtolower(ltrim($extension, '.'));
        if (isset(self::$configParsers[$extension])) {
            unset(self::$configParsers[$extension]);
            return true;
        }
        return false;
    }
}