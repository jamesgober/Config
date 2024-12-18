<?php

declare(strict_types=1);

namespace JG\Tests;

use JG\Config\Config;
use PHPUnit\Framework\TestCase;
use JG\Config\ConfigParserFactory;
use JG\Config\Exceptions\ConfigException;

class ConfigTest extends TestCase

{
    public function testSetConfigPathValid(): void
    {
        $config = new Config();
        $configPath = __DIR__ . '/config';
        $config->setConfigPath($configPath);

        $this->assertDirectoryExists($configPath);
    }

    public function testSetConfigPathInvalid(): void
    {
        $this->expectException(ConfigException::class);

        $config = new Config();
        $config->setConfigPath('/invalid/path');
    }

    public function testSetFlatten(): void
    {
        $config = new Config();
        $config->setFlatten(false);

        // Assert by invoking protected method via reflection
        $reflection = new \ReflectionClass($config);
        $property = $reflection->getProperty('flatten');
        $property->setAccessible(true);

        $this->assertFalse($property->getValue($config));
    }

    public function testLoadValidFile(): void
    {
        $config = new Config(__DIR__ . '/config', true);
        $config->load('config.json');
    
        $this->assertTrue($config->has('config.database.host'));
        $this->assertEquals('localhost', $config->get('config.database.host'));
    }

    public function testLoadValidFileWithFlattening(): void
    {
        $config = new Config(__DIR__ . '/config');
        $config->setFlatten(false); // Disable flattening
        $config->load('config.json');
    
        // Assert non-flattened keys
        $this->assertTrue($config->has('database'));
        $this->assertArrayHasKey('host', $config->get('database'));
        $this->assertEquals('localhost', $config->get('database')['host']);
    }

    public function testLoadInvalidFile(): void
    {
        $this->expectException(ConfigException::class);

        $config = new Config(__DIR__ . '/config');
        $config->load('nonexistent.json');
    }

    public function testAddAndGet(): void
    {
        $config = new Config();
        $config->add('database.host', 'localhost');

        $this->assertEquals('localhost', $config->get('database.host'));
    }

    public function testAddToGroup(): void
    {
        $config = new Config();
        $config->add('app.debug', true);
        $config->add('app.cache', 'enabled');

        $this->assertTrue($config->get('app.debug'));
        $this->assertEquals('enabled', $config->get('app.cache'));
    }

    public function testDeleteKey(): void
    {
        $config = new Config();
        $config->add('app.debug', true);
        $config->delete('app.debug');

        $this->assertFalse($config->has('app.debug'));
    }

    public function testDeleteGroup(): void
    {
        $config = new Config();
        $config->add('app.debug', true);
        $config->add('app.cache', 'enabled');
    
        $config->delete('app');
    
        $this->assertFalse($config->has('app.debug'));
        $this->assertFalse($config->has('app.cache'));
        $this->assertFalse($config->has('app'));
    }

    public function testClear(): void
    {
        $config = new Config();

        $config->add('app.debug', true);
        $config->add('database.host', 'localhost');
        $config->clear();

        $this->assertFalse($config->has('app.debug'));
        $this->assertFalse($config->has('database.host'));
    }

    public function testFetch(): void
    {
        $config = new Config(__DIR__ . '/config');
        $result = $config->fetch('config.json');

        $this->assertEquals('localhost', $result['database']['host']);
    }

    public function testFetchInvalidFile(): void
    {
        $this->expectException(ConfigException::class);

        $config = new Config(__DIR__ . '/config');
        $config->fetch('nonexistent.json');
    }

    public function testSaveCache(): void
    {
        $config = new Config();
        $config->add('app.debug', true);
        $config->add('database.host', 'localhost');

        $filePath = __DIR__ . '/cache.json';
        $this->assertTrue($config->saveCache($filePath, Config::EXPIRE_ONE_DAY));
        $this->assertFileExists($filePath);

        // Cleanup
        unlink($filePath);
    }

    public function testLoadCache(): void
    {
        $config = new Config();
        $filePath = __DIR__ . '/cache.json';

        // Prepare cache data
        $cacheData = [
            'config' => ['app.debug' => true, 'database.host' => 'localhost'],
            'groups' => ['app' => ['app.debug'], 'database' => ['database.host']],
            'expires' => time() + 3600,
        ];
        file_put_contents($filePath, json_encode($cacheData));

        $this->assertTrue($config->loadCache($filePath));
        $this->assertTrue($config->has('app.debug'));
        $this->assertEquals('localhost', $config->get('database.host'));

        // Cleanup
        unlink($filePath);
    }

    public function testLoadExpiredCache(): void
    {
        $config = new Config();
        $filePath = __DIR__ . '/cache.json';

        // Prepare expired cache data
        $cacheData = [
            'config' => ['app.debug' => true, 'database.host' => 'localhost'],
            'groups' => ['app' => ['app.debug'], 'database' => ['database.host']],
            'expires' => time() - 3600,
        ];
        file_put_contents($filePath, json_encode($cacheData));

        $this->assertFalse($config->loadCache($filePath));
        $this->assertFileDoesNotExist($filePath); // File should be deleted

        // Cleanup
        @unlink($filePath);
    }

    public function testDeleteCache(): void
    {
        $config = new Config();
        $filePath = __DIR__ . '/cache.json';

        // Create a dummy cache file
        file_put_contents($filePath, json_encode(['dummy' => true]));

        $this->assertFileExists($filePath);
        $this->assertTrue($config->deleteCache($filePath));
        $this->assertFileDoesNotExist($filePath);
    }

    public function testMaxDepthExceeded(): void
    {
        $config = new Config();
        $config->setMaxDepth(2);
    
        $nestedConfig = [
            'level1' => [
                'level2' => [
                    'level3' => [ // This should exceed max depth
                        'key' => 'value',
                    ],
                ],
            ],
        ];
    
        $this->expectException(ConfigException::class);
    
        // Use reflection to call the protected `flattenArray` directly
        $reflection = new \ReflectionClass($config);
        $method = $reflection->getMethod('flattenArray');
        $method->setAccessible(true);
    
        $method->invoke($config, $nestedConfig, 'nested');
    }
    
    public function testEmptyGroupHandling(): void
    {
        $config = new Config();
        $config->add('group.empty', []);
        $this->assertTrue($config->has('group.empty'));
    
        $config->delete('group.empty');
        $this->assertFalse($config->has('group.empty'));
    }

    public function testNonUtf8File(): void
    {
        $config = new Config(__DIR__ . '/config');

        $filePath = __DIR__ . '/config/non_utf8.json';
    
        // Simulate non-UTF-8 file
        $data = '{"key":"value"}';
        file_put_contents($filePath, mb_convert_encoding($data, 'ISO-8859-1'));
    
        // Mock the encoding check
        $mockedFilePath = __DIR__ . '/config/mocked_non_utf8.json';
        file_put_contents($mockedFilePath, "\x80\x81\x82"); // Invalid UTF-8 sequence
    
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage("File encoding must be UTF-8");
    
        // Force the parser to encounter the mocked data
        $config->load(basename($mockedFilePath));
    
        // Cleanup
        unlink($filePath);
    }

    public function setUp(): void
    {
        parent::setUp();
    
        ConfigParserFactory::registerParser('custom', \JG\Tests\CustomParser::class);
    }

    public function testCustomParser(): void
    {
        $config = new Config(__DIR__ . '/config/', false);
        $config->load('config.custom');
    
        $this->assertEquals('value1', $config->get('key1'));
        $this->assertEquals('value2', $config->get('key2'));
    }

    public function testInvalidCacheStructure(): void
    {
        $config = new Config();
        $filePath = __DIR__ . '/cache.json';
    
        // Malformed cache data
        file_put_contents($filePath, json_encode(['invalid' => 'data']));
    
        $this->expectException(ConfigException::class);
        $config->loadCache($filePath);
    
        // Cleanup
        unlink($filePath);
    }

    public function testLoadPerformance(): void
    {
        $config = new Config();
    
        $largeConfig = [];
        for ($i = 0; $i < 10000; $i++) {
            $largeConfig["key{$i}"] = "value{$i}";
        }
    
        // Use reflection to access the protected `insert` method
        $reflection = new \ReflectionClass($config);
        $method = $reflection->getMethod('insert');
        $method->setAccessible(true);
    
        $method->invoke($config, $largeConfig);
    
        $this->assertEquals('value9999', $config->get('key9999'));
    }
}