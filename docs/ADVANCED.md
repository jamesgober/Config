<h1 align="center">
    <picture picture>
        <source media="(prefers-color-scheme: dark)" srcset=".//media/jamesgober-logo-dark.png">
        <img width="72" height="72" alt="Official brand mark and logo of James Gober. Image shows JG stylish initials encased in a hexagon outline." src="./media/jamesgober-logo.png">
    </picture>
    <br>
    <b>ADVANCED FEATURES</b>
</h1>


Welcome to the advanced section of **JG\Config**! This document delves deeper into the library's powerful features that cater to complex and high-performance applications.

&nbsp;

## 1. **Custom Parsers**
Extend the library to support additional file formats by registering your custom parsers.

### Example: Registering a Custom Parser
```php
use JG\Config\ConfigParserFactory;

// Register a custom parser for `.custom` files
ConfigParserFactory::registerParser('custom', MyCustomParser::class);
```

&nbsp;

### Example: Writing a Custom Parser
To create a custom parser, implement the `ParserInterface`:
```php
use JG\Config\Parsers\ParserInterface;

class MyCustomParser implements ParserInterface {
    public function parse(string $filePath): array {
        // Custom parsing logic
    }
}
```

&nbsp;

## 2. **Dynamic Flattening Control**
By default, the library flattens nested configuration keys into dot notation. You can toggle this behavior dynamically.

### Example: Disabling Flattening
```php
use JG\Config\Config;

$config = new Config('/path/to/config', false); // Disable flattening
$config->load('nested_config.json');

// Access without flattening
$nested = $config->get('database.credentials');
```

&nbsp;

## 3. **Advanced Caching**
Leverage robust caching to optimize performance and improve access times.

### Saving Configurations to Cache
```php
$config->saveCache('/path/to/cache.json', Config::EXPIRE_ONE_DAY);
```

&nbsp;

### Loading Configurations from Cache
```php
if ($config->loadCache('/path/to/cache.json')) {
    echo "Loaded from cache!";
}
```

&nbsp;

### Deleting Cache
```php
$config->deleteCache('/path/to/cache.json');
```

&nbsp;

## 4. **Stream Parsing**
Load configurations directly from streams, such as PSR-7 compatible interfaces.

### Example: Parsing a Stream
```php
use Psr\Http\Message\StreamInterface;

$config->loadFromStream($stream);
```

&nbsp;

## 5. **Error and Debugging Utilities**
Identify and resolve configuration issues with informative error messages and detailed exception handling.

### Example: Handling Errors Gracefully
```php
use JG\Config\Exceptions\ConfigException;

try {
    $config->load('invalid_file.xyz');
} catch (ConfigException $e) {
    echo "Error: " . $e->getMessage();
}
```

&nbsp;

## 6. **Max Depth Control**
Prevent performance issues and improve manageability by controlling the maximum depth for nested configurations.

### Example: Setting Maximum Depth
```php
$config->setMaxDepth(2); // Enforces a depth limit of 2
$config->add('nested', [
    'level1' => [
        'level2' => [
            'level3' => 'value' // This will throw an exception
        ]
    ]
]);
```

&nbsp;

## 7. **Group Management**
Organize configuration data into manageable groups.

### Example: Adding to Groups
```php
$config->add('app.name', 'TestApp');
$config->add('app.version', '1.0.0');

// Access grouped keys
echo $config->get('app.name'); // Outputs: TestApp
```

&nbsp;

### Example: Deleting Groups
```php
$config->delete('app'); // Removes all keys within the `app` group
```

&nbsp;

## 8. **Performance Optimizations**
Designed for high performance, the library supports:
- Efficient caching mechanisms.
- Lazy loading to avoid unnecessary overhead.
- Optimized key-value lookups, even in deeply nested configurations.

&nbsp;

## 9. **Integration with PSR Standards**
The library is built with compatibility in mind:
- Works seamlessly with PSR-7 for stream-based operations.
- Adheres to PSR-12 for coding standards.

&nbsp;

---

## Contribution
If you have ideas to expand these advanced features, check out our **[Contribution Guidelines](../.github/CONTRIBUTING.md)** for details on how to get involved.

