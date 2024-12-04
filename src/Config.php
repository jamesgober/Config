<?php
/**
 * Config.php
 * 
 * The core configuration management class.
 * Provides methods to load, parse, and manage configuration data from 
 * various sources, including JSON, YAML, XML, and more.
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

use \ltrim;
use \rtrim;
use \is_dir;
use \strtok;
use \explode;
use \implode;
use \is_file;
use \strtolower;
use \array_merge;
use \array_shift;
use \preg_replace;
use \str_contains;
use \array_key_exists;
use \JG\Config\ConfigParserFactory;
use \JG\Config\Exceptions\ConfigException;

/**
 * Configuration Manager
 * 
 * Manages the loading, parsing, and handling of configuration files.
 * Supports multiple formats such as JSON, YAML, and XML, with the ability
 * to extend via custom parsers.
 * 
 * @package JG\Config
 */
class Config
{
    /**
     * Default configuration directory path.
     *
     * @var string|null
     */
    private ?string $configPath = null;

    /**
     * Whether configuration keys should be flattened.
     *
     * @var bool
     */
    private bool $flatten = true;

    /**
     * Grouped configuration storage.
     *
     * Stores configurations by groups, allowing access by original
     * source filename or unique key.
     *
     * @var array<string, array<string, string>>
     */
    private array $groups = [];

    /**
     * Primary configuration storage.
     *
     * Stores all configuration key-value pairs, including unique keys
     * generated from grouped configurations.
     *
     * @var array<string, mixed>
     */
    private array $config = [];

    /**
     * Constructor.
     *
     * @param string|null $configPath Default path for config files (optional).
     * @param bool $flattenConfig Whether to enable key flattening (default: true).
     */
    public function __construct(?string $configPath = null, bool $flattenConfig = true)
    {
        $this->setConfigPath($configPath);
        $this->setFlatten($flattenConfig);
    }

    /**
     * Sets the configuration directory path.
     *
     * @param string|null $path Absolute or relative path to the config directory.
     * @return void
     * 
     * @throws ConfigException If the path is invalid.
     */
    public function setConfigPath(?string $path = null): void
    {
        if ($path && !is_dir($path)) {
            throw new ConfigException("Invalid configuration directory: {$path}");
        }
        $this->configPath = $path ? rtrim($path, '/') . '/' : null;
    }

    /**
     * Enables or disables key flattening.
     *
     * @param bool $flatten Whether to enable flattening.
     * @return void
     */
    public function setFlatten(bool $flatten): void
    {
        $this->flatten = $flatten;
    }

    /**
     * Resolves the full file path.
     *
     * @param string|null $filePath The file path or filename (optional).
     * @return string|null The resolved file path, or null if unresolved.
     */
    private function resolvePath(?string $filePath): ?string
    {
        if (!$filePath) {
            return null;
        }

        if (is_file($filePath)) {
            return $filePath;
        }

        return $this->configPath ? $this->configPath . ltrim($filePath, '/') : null;
    }

    /**
     * Inserts configuration data into the manager.
     *
     * @param array<string, mixed> $config Configuration key-value pairs.
     * @param array<string, array<string, string>> $groups Configuration groups.
     * @return void
     */
    protected function insert(array $config = [], array $groups = []): void
    {
        $this->config = array_merge($this->config, $config);
        $this->groups = array_merge($this->groups, $groups);
    }

    /**
     * Flattens a multidimensional array into dot-notated keys and groups.
     *
     * @param array $data The input array to flatten.
     * @param string $baseKey The base key for flattening.
     * @return array An array containing two elements:
     *               - The first element is an associative array of flattened key-value pairs.
     *               - The second element is an associative array of flattened key mappings to original keys.
     */
    private function flattenArray(array $data, string $baseKey): array
    {
        $flattenedData = [];
        $groups = [];
    
        foreach ($data as $key => $value) {
            $fullKey = "{$baseKey}.{$key}";
    
            if (is_array($value)) {
                [$nestedFlattened, $nestedGroups] = $this->flattenArray($value, $fullKey);
    
                // Directly merge instead of using array_merge
                foreach ($nestedFlattened as $nestedKey => $nestedValue) {
                    $flattenedData[$nestedKey] = $nestedValue;
                }
                foreach ($nestedGroups as $nestedKey => $originalKey) {
                    $groups[$nestedKey] = $originalKey;
                }
            } else {
                $flattenedData[$fullKey] = $value;
                $groups[$fullKey] = $key;
            }
        }
    
        return [$flattenedData, $groups];
    }

    /**
     * Loads configuration data from a file.
     *
     * Reads the specified file, parses its contents, and merges 
     * the data into the existing configuration. When flattening is enabled,
     * keys are grouped by the file's base name to create unique keys. If a key 
     * already exists, it will be overwritten.
     *
     * @param string|null $filePath Absolute or relative path to the file.
     * @return bool True if the configuration was successfully loaded.
     * @throws ConfigException If the file cannot be found or parsed.
     */
    public function load(?string $filePath = null): bool
    {
        $filePath = $this->resolvePath($filePath);
        if (!$filePath || !is_file($filePath)) {
            throw new ConfigException("Configuration file not found: {$filePath}");
        }
    
        // Parse the configuration file
        $parsedData = $this->parse($filePath);
        if ($parsedData === null) {
            throw new ConfigException("Failed to parse configuration file: {$filePath}");
        }
    
        if ($this->flatten) {
            // Flatten the configuration data
            $baseName = strtolower(preg_replace('/\.[^.]+$/', '', basename($filePath)));
            [$flattenedData, $groups] = $this->flattenArray($parsedData, $baseName);
    
            // Insert the flattened data
            $this->insert($flattenedData, [$baseName => $groups]);
            return true;
        }

        // Insert non-flattened data
        $this->insert($parsedData);
        return true;
    }

    /**
     * Parses a configuration file.
     *
     * @param string|null $filePath Absolute or relative path to the file.
     * @return array<string, mixed>|null Parsed configuration data, or null if parsing fails.
     * 
     * @throws ConfigException If the file does not exist or cannot be parsed.
     */
    protected function parse(?string $filePath = null): ?array
    {
        $filePath = $this->resolvePath($filePath);

        if (!$filePath || !is_file($filePath)) {
            throw new ConfigException("Configuration file not found: {$filePath}");
        }

        $parser = ConfigParserFactory::createParser($filePath);
        if (!$parser) {
            throw new ConfigException("No suitable parser found for: {$filePath}");
        }

        return $parser->parse($filePath);
    }

    /**
     * Retrieves configuration from a file.
     *
     * @param string|null $filePath Path to the configuration file.
     * @return array<string, mixed> The parsed configuration data.
     */
    public function fetch(?string $filePath = null): array
    {
        return $this->parse($filePath) ?? [];
    }

    /**
     * Checks if a specific configuration key exists.
     *
     * This method first checks if the key exists in the main configuration.
     * If not, it then checks whether the key corresponds to a group in the grouped configurations.
     *
     * @param string $key The configuration key to check.
     * @return bool True if the key or group exists; otherwise, false.
     */
    public function has(string $key): bool
    {
        // Check if the key exists in the main configuration array
        if (array_key_exists($key, $this->config)) {
            return true;
        }

        // Check if the key exists as a group
        return array_key_exists($key, $this->groups);
    }

    /**
     * Adds or updates a configuration value.
     *
     * If the key contains dots, indicating a grouped or flattened list,
     * the method ensures the group and config data are updated accordingly.
     *
     * @param string $key The configuration key (supports dot notation for groups).
     * @param mixed $value The configuration value.
     * @return void
     */
    public function add(string $key, mixed $value): void
    {
        // Add to config
        $this->config[$key] = $value;

        // If the key is dot-notated, update the group
        if (str_contains($key, '.')) {
            $parts = explode('.', $key);
            $group = array_shift($parts);
            $subKey = implode('.', $parts);

            // Ensure the group exists
            $this->groups[$group][$key] = $subKey;
        }
    }

    /**
     * Retrieves a configuration value.
     *
     * @param string $key The configuration key.
     * @param mixed $default Default value if the key is not found.
     * @return mixed|null The configuration value, or the default.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Deletes a configuration key or group.
     *
     * If the provided key corresponds to a group, all keys in that group
     * will be removed from the configuration array before the group itself
     * is deleted.
     *
     * @param string $key The key or group to delete.
     * @return void
     */
    public function delete(string $key): void
    {
        if (isset($this->groups[$key])) {
            // Delete all group keys from config
            foreach ($this->groups[$key] as $fullKey => $subKey) {
                unset($this->config[$fullKey]);
            }
            unset($this->groups[$key]);
            return;
        }
    
        // Delete single key
        unset($this->config[$key]);
    }

    /**
     * Clears all configuration data.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->config = [];
        $this->groups = [];
    }
}