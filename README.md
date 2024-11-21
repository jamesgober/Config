# Config

**Config** is a modular PHP library for loading, managing, and extending configurations. It supports multiple formats out of the box, including JSON, XML, YAML, INI, and PHP arrays, with the flexibility to add custom parsers.

## Features

- Load configurations from multiple file formats: JSON, XML, YAML, INI, and PHP arrays.
- Manage configuration data in an organized, extendable way.
- Support for custom parsers to handle additional file formats.
- Comprehensive error handling with custom exceptions.
- Lightweight and easy-to-use API.

---

## Installation

Install via Composer:

```bash
composer require jamesgober/config
```

---

## Quick Start

### Loading Configuration Files

```php
use JG\Config\Config;

$config = new Config('/path/to/config/files');

// Load a configuration file
$config->load('database.json');
$config->load('app.xml');

// Access configuration values
$dbHost = $config->get('database.host', 'default_host');

// Check if a configuration key exists
if ($config->has('app.debug')) {
    echo "Debug mode is enabled!";
}

// Add or update configuration values
$config->add('app.name', 'MyApp');
```

### Custom Parsers

You can extend the library by registering custom parsers:

```php
use JG\Config\ConfigParserFactory;

// Register a custom parser for `.custom` files
ConfigParserFactory::registerParser('custom', MyCustomParser::class);
```

### Supported Formats

- **JSON**: `config.json`
- **XML**: `config.xml`
- **YAML**: `config.yaml`
- **INI**: `config.ini`
- **PHP Array**: `config.php`

#### config.php
```php
<?php
return [
    'host' => 'localhost',
    'port' => 3306,
    'user' => 'root',
    'password' => 'secret',
];
```

#### config.json
```json
{
    "host": "localhost",
    "port": 3306,
    "user": "root",
    "password": "secret"
}
```

#### config.ini
```ini
[app]
name    = "TestApp"
version = "1.0.0"
debug   = true
cache   = null
```

#### config.xml
```xml
<app>
    <name>TestApp</name>
    <version>1.0.0</version>
    <debug>true</debug>
    <cache>null</cache>
</app>
```

#### config.yaml
```yaml
database:
    host: localhost
    port: 3306
    user: root
    password: secret

app:
    debug: true
    cache: null
```

---

## Error Handling

The library uses custom exceptions for error management:

- `JG\Config\Exceptions\ConfigException`: Base exception for all configuration-related errors.
- `JG\Config\Exceptions\ConfigParseException`: Thrown when a configuration file cannot be parsed.
- `JG\Config\Exceptions\InvalidParserException`: Thrown when an invalid or unsupported parser is encountered.

Example:
```php
try {
    $config->load('missing_file.json');
} catch (ConfigException $e) {
    echo "Error loading configuration: " . $e->getMessage();
}
```


---

## Directory Structure

```
src/
├── Config.php
├── ConfigParserFactory.php
├── Exceptions/
│   ├── ConfigException.php
│   ├── ConfigParseException.php
│   └── InvalidParserException.php
├── Parsers/
│   ├── ParserInterface.php
│   ├── PhpParser.php
│   ├── JsonParser.php
│   ├── IniParser.php
│   ├── XmlParser.php
│   └── YamlParser.php
tests/
...
```

---

## Contributing

We welcome contributions! Please submit pull requests or report issues on [GitHub](https://github.com/jamesgober/Config).

---

## License

This library is open-source software licensed under the [MIT license](LICENSE).
