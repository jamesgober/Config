<?php

declare(strict_types=1);

namespace JG\Tests\Parsers;

use PHPUnit\Framework\TestCase;
use JG\Config\Parsers\YamlParser;
use JG\Config\Exceptions\ConfigParseException;

class YamlParserTest extends TestCase
{
    public function testYamlParser()
    {
        $parser = new YamlParser();
        $filePath = __DIR__ . '/../config/config.yaml';

        $config = $parser->parse($filePath);

        $this->assertEquals('localhost', $config['database']['host']);
        $this->assertEquals(3306, $config['database']['port']);
        $this->assertTrue($config['app']['debug']);
        $this->assertNull($config['app']['cache']);
    }

    public function testYamlParserInvalidFile()
    {
        $this->expectException(ConfigParseException::class);

        $parser = new YamlParser();
        $parser->parse(__DIR__ . '/../config/nonexistent.yaml');
    }
}