<?php
/**
 * Config.php
 * 
 * The core configuration management class.
 * Provides methods to load, parse, and manage configuration data from 
 * various sources, including conf, ini, json, yaml/yml, xml, and more.
 * 
 * PHP version 8.2
 *
 * @package    JG\Config
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <code@jamesgober.com>
 * @link       https://github.com/jamesgober/Config
 * @license    MIT License
 * @copyright  2024 James Gober (https://jamesgober.com)
 */
declare(strict_types=1);

namespace JG\Config;

use \ltrim;
use \mkdir;
use \rtrim;
use \is_dir;
use \unlink;
use \is_file;
use \is_array;
use \strtolower;
use \array_merge;
use \array_shift;
use \json_decode;
use \json_encode;
use \preg_replace;
use \array_key_exists;
use \file_put_contents;
use \JSON_PRETTY_PRINT;
use \JSON_THROW_ON_ERROR;
use JG\Config\ConfigParserFactory;
use Psr\Http\Message\StreamInterface;
use JG\Config\Exceptions\ConfigException;

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
    /** Common expiration durations in seconds. */
    public const EXPIRE_NEVER     = 0;
    public const EXPIRE_ONE_HOUR  = 3600;
    public const EXPIRE_HALF_DAY  = 43200;
    public const EXPIRE_ONE_DAY   = 86400;
    public const EXPIRE_ONE_WEEK  = 604800;
    public const EXPIRE_ONE_MONTH = 2592000;

    /**
     * Default configuration directory path.
     *
     * @var string|null $configPath
     */
    private ?string $configPath = null;

    /**
     * Whether configuration keys should be flattened.
     *
     * @var bool $flatten
     */
    private bool $flatten = true;

    /**
     * Maximum allowed depth for nested configurations.
     *
     * This property sets a limit on how deeply nested configurations 
     * can be processed by the flattening logic. Exceeding this depth 
     * will result ina `ConfigException` being thrown to prevent 
     * performance degradation and manageability issues.
     *
     * Default: 3
     *
     * @var int $maxDepth
     */
    private int $maxDepth = 3;

    /**
     * Indicates whether the configuration cache has been loaded.
     *
     * @var bool$cacheLoaded
     */
    private bool $cacheLoaded = false;

    /**
     * Grouped configuration storage, allowing access by group or 
     * unique key.
     *
     * @var array<string, array<string, string>> $groups
     */
    private array $groups = [];

    /**
     * Primary configuration storage, including unique keys from 
     * grouped configurations.
     *
     * @var array<string, mixed> $config
     */
    private array $config = [];

    /**
     * Constructor.
     *
     * Initializes the configuration manager with optional defaults.
     *
     * @param string|null $configPath Optional path for configuration files.
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
     * @throws ConfigException If the provided path is invalid.
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
     * Sets the maximum depth for nested configurations.
     *
     * @param int $depth Maximum depth to allow.
     * @return void
     * 
     * @throws ConfigException If the depth is less than 1.
     */
    public function setMaxDepth(int $depth): void
    {
        if ($depth < 1) {
            throw new ConfigException("Maximum depth must be at least 1.");
        }
        $this->maxDepth = $depth;
    }

    /**
     * Checks if the configuration cache has been loaded.
     *
     * @return bool True if the cache has been loaded; otherwise, false.
     */
    public function isCacheLoaded(): bool
    {
        return $this->cacheLoaded;
    }

    /**
     * Resolves the full file path.
     *
     * @param string|null $filePath The file path or filename (optional).
     * @return string|null The resolved file path, or null if unresolved.
     */
    private function resolvePath(?string $filePath): ?string
    {
        if (!$filePath || (!is_file($filePath) && !$this->configPath)) {
            return null;
        }

        return is_file($filePath) ? $filePath : $this->configPath . ltrim($filePath, '/');
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
     * Merges new configuration data into the existing configuration.
     *
     * @param array<string, mixed> $newConfig The new configuration data.
     * @return void
     */
    public function mergeConfig(array $newConfig): void
    {
        $this->config = array_merge($this->config, $newConfig);
    }

    /**
     * Flattens a nested configuration array.
     *
     * Converts nested arrays into dot-notated keys.
     *
     * @param array<string, mixed> $data The input array to flatten.
     * @param string $baseKey The base key for flattening.
     * @param int $depth Current depth of the flattening process.
     * @return array<string, mixed>
     * 
     * @throws ConfigException If the maximum depth is exceeded.
     */
    private function flattenArray(array $data, string $baseKey): array
    {
        $flattenedData = [];
        $groups = [];
        $stack = [['data' => $data, 'key' => $baseKey, 'depth' => 0]];
    
        while ($stack) {
            $current = array_pop($stack);
            $currentData = $current['data'];
            $currentKey = $current['key'];
            $currentDepth = $current['depth'];
    
            if ($currentDepth >= $this->maxDepth) { // Correctly handle "max depth exceeded"
                throw new ConfigException("Maximum depth of {$this->maxDepth} exceeded at key: {$currentKey}");
            }
    
            foreach ($currentData as $key => $value) {
                $fullKey = "{$currentKey}.{$key}";
    
                if (is_array($value)) {
                    $stack[] = ['data' => $value, 'key' => $fullKey, 'depth' => $currentDepth + 1];
                } else {
                    $flattenedData[$fullKey] = $value;
                    $groups[$fullKey] = $key;
                }
            }
        }
    
        return [$flattenedData, $groups];
    }

    /**
     * Loads configuration data from a file.
     *
     * Reads the specified file, parses its contents, and merges the data
     * into the existing configuration. If the cache is already loaded,
     * this method skips further processing.
     *
     * @param string|null $filePath The file path or name (optional).
     * @return bool True if the configuration was successfully loaded.
     * 
     * @throws ConfigException If the file cannot be found or parsed.
     */
    public function load(?string $filePath = null): bool
    {
        if ($this->isCacheLoaded()) {
            return true;
        }

        $filePath = $this->resolvePath($filePath);

        if (!$filePath || !is_file($filePath)) {
            throw new ConfigException("Configuration file not found: {$filePath}");
        }

        $parsedData = $this->parse($filePath);
        if (!is_array($parsedData)) {
            throw new ConfigException("Invalid configuration format in file: {$filePath}. Expected an array.");
        }

        if ($this->flatten) {
            $baseName = strtolower(pathinfo($filePath, PATHINFO_FILENAME));
            [$flattenedData, $groups] = $this->flattenArray($parsedData, $baseName);
            $this->insert($flattenedData, [$baseName => $groups]);
        } else {
            $this->insert($parsedData);
        }

        return true;
    }

    /**
     * Loads configuration data from multiple files.
     *
     * @param array<string> $filePaths List of file paths.
     * @return void
     * 
     * @throws ConfigException If any file cannot be loaded or parsed.
     */
    public function loadMultiple(array $filePaths): void
    {
        foreach ($filePaths as $filePath) {
            $this->load($filePath);
        }
    }

    /**
     * Loads configuration data from a stream (e.g., PSR-7 StreamInterface).
     *
     * @param StreamInterface $stream A PSR-7 compatible stream.
     * @return bool True if the configuration was successfully loaded.
     * 
     * @throws ConfigException If the stream cannot be parsed.
     */
    public function loadFromStream(StreamInterface $stream): bool
    {
        $data = json_decode($stream->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($data)) {
            throw new ConfigException("Failed to parse configuration stream.");
        }

        $this->insert($data);
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
     * Validates the structure of configuration and group data.
     *
     * @param array $config The configuration data.
     * @param array $groups The groups data.
     * @return void
     * 
     * @throws ConfigException If the structures are invalid.
     */
    private function validateConfigStructure(array $config, array $groups): void
    {
        if (!is_array($config) || !is_array($groups)) {
            throw new ConfigException("Invalid configuration structure: Both 'config' and 'groups' must be arrays.");
        }
    }

    /**
     * Checks if a specific configuration key exists.
     *
     * @param string $key The key to check.
     * @return bool True if the key exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->config) || array_key_exists($key, $this->groups);
    }

    /**
     * Adds or updates a configuration value.
     *
     * Optimized to ensure group structures are maintained efficiently.
     *
     * @param string $key The configuration key (supports dot notation for groups).
     * @param mixed $value The configuration value.
     * @return void
     */
    public function add(string $key, mixed $value): void
    {
        $this->config[$key] = $value;

        // If the key is dot-notated, update the group
        if (str_contains($key, '.')) {
            $parts = explode('.', $key);
            $group = array_shift($parts);

            // Avoid redundant structure initialization
            if (!isset($this->groups[$group])) {
                $this->groups[$group] = [];
            }

            $this->groups[$group][$key] = implode('.', $parts);
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
     * Removes a specific key or group from the configuration. If the key represents
     * a group, all associated keys within the group will also be removed.
     *
     * @param string $key The key or group to delete.
     * @return void
     * 
     * @throws ConfigException If the group structure is invalid.
     */
    public function delete(string $key): void
    {
        // Validate group structure if the key is in groups
        if (isset($this->groups[$key])) {
            if (!is_array($this->groups[$key])) {
                throw new ConfigException("Invalid group structure for key: {$key}. Expected an array.");
            }

            // Remove all keys in the group
            foreach ($this->groups[$key] as $fullKey => $subKey) {
                unset($this->config[$fullKey]);
            }

            unset($this->groups[$key]);
            return;
        }

        // Remove single key
        unset($this->config[$key]);
    }

    /**
     * Retrieves the entire configuration array.
     *
     * @return array<string, mixed> The configuration array.
     */
    public function getAll(): array
    {
        return $this->config;
    }

    /**
     * Retrieves all configuration groups.
     *
     * @return array<string, array<string, string>> The groups array.
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * Resets the configuration groups to an empty state.
     *
     * @return void
     */
    public function resetGroups(): void
    {
        $this->groups = [];
    }

    /**
     * Saves the current configuration to a cache file.
     *
     * @param string $filePath File path to save cache.
     * @param int $expires Expiration time (0 for no expiration).
     * @return bool True if successful; otherwise, false.
     */
    public function saveCache(string $filePath, int $expires = 0): bool
    {
        if (!is_array($this->config) || !is_array($this->groups)) {
            throw new ConfigException("Cannot save cache: 'config' and 'groups' must be arrays.");
        }

        $dir = dirname($filePath);
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            return false;
        }

        $data = [
            'config' => $this->config,
            'groups' => $this->groups,
            'expires' => $expires ? time() + $expires : 0,
        ];

        return file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT)) !== false;
    }

    /**
     * Loads configuration and groups from a cache file.
     *
     * @param string $filePath Path to the cache file.
     * @return bool True if the cache was loaded successfully; otherwise, false.
     * 
     * @throws ConfigException If the cache format is invalid.
     */
    public function loadCache(string $filePath): bool
    {
        if ($this->cacheLoaded) {
            return true;
        }

        if (!is_file($filePath) || !is_readable($filePath)) {
            return false;
        }

        $data = json_decode(file_get_contents($filePath), true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($data['config']) || !is_array($data['groups'])) {
            throw new ConfigException("Invalid cache format: 'config' and 'groups' must be arrays.");
        }

        if (isset($data['expires']) && $data['expires'] > 0 && time() > $data['expires']) {
            $this->deleteCache($filePath);
            return false;
        }

        $this->config = $data['config'];
        $this->groups = $data['groups'];
        $this->cacheLoaded = true;

        return true;
    }

    /**
     * Deletes a cache file.
     *
     * @param string $filePath Path to cache.
     * @return bool True if deleted.
     */
    public function deleteCache(string $filePath): bool
    {
        return is_file($filePath) && unlink($filePath);
    }

    /**
     * Clears the configuration manager.
     */
    public function clear(): void
    {
        $this->groups = $this->config = [];
        $this->cacheLoaded = false;
    }
}