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

    public function testInsert(): void
    {
        $config = new Config();

        $data = [
            'app.debug' => true,
            'app.cache' => null,
        ];

        $groups = [
            'app' => [
                'debug' => 'app.debug',
                'cache' => 'app.cache',
            ],
        ];

        $config->insert($data, $groups);

        $this->assertTrue($config->has('app.debug'));
        $this->assertNull($config->get('app.cache'));
    }

    public function testAddAndGet(): void
    {
        $config = new Config();
        $config->add('database.host', 'localhost');

        $this->assertEquals('localhost', $config->get('database.host'));
    }

    public function testDelete(): void
    {
        $config = new Config();

        $config->add('app.debug', true);
        $config->delete('app.debug');

        $this->assertFalse($config->has('app.debug'));
    }

    public function testClear(): void
    {
        $config = new Config();

        $config->add('app.debug', true);
        $config->clear();

        $this->assertFalse($config->has('app.debug'));
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