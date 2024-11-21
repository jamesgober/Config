<?php

declare(strict_types=1);

namespace JG\Tests\Parsers;

use PHPUnit\Framework\TestCase;
use JG\Config\Parsers\JsonParser;
use JG\Config\Exceptions\ConfigParseException;

class JsonParserTest extends TestCase
{
    public function testJsonParser()
    {
        $parser = new JsonParser();
        $filePath = __DIR__ . '/../config/config.json';

        $config = $parser->parse($filePath);

        $this->assertEquals('localhost', $config['database']['host']);
        $this->assertEquals(3306, $config['database']['port']);
        $this->assertTrue($config['app']['debug']);
        $this->assertNull($config['app']['cache']);
    }

    public function testJsonParserInvalidFile()
    {
        $this->expectException(ConfigParseException::class);

        $parser = new JsonParser();
        $parser->parse(__DIR__ . '/../config/nonexistent.json');
    }
}