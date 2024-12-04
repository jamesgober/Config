<?php

declare(strict_types=1);

namespace JG\Tests;

use JG\Config\Config;
use PHPUnit\Framework\TestCase;
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
        $config = new Config(__DIR__ . '/config');
        $config->load('config.json');

        $this->assertTrue($config->has('database.host'));
        $this->assertEquals('localhost', $config->get('database.host'));
    }

    public function testLoadValidFileWithFlattening(): void
    {
        $config = new Config(__DIR__ . '/config');
        $config->setFlatten(true);
        $config->load('config.json');

        $this->assertTrue($config->has('config.database.host'));
        $this->assertEquals('localhost', $config->get('config.database.host'));
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
}