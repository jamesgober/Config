<?php

declare(strict_types=1);

namespace JG\Tests\Parsers;

use PHPUnit\Framework\TestCase;
use JG\Config\Parsers\XmlParser;
use JG\Config\Exceptions\ConfigParseException;

class XmlParserTest extends TestCase
{
    public function testXmlParser(): void
    {
        $filePath = __DIR__ . '/../config/config.xml';
        $parser = new XmlParser();
    
        $result = $parser->parse($filePath);
    
        // Debugging the result
        var_dump($result);
    
        $this->assertIsArray($result);
        $this->assertArrayHasKey('app', $result);
        $this->assertArrayHasKey('name', $result['app']);
        $this->assertEquals('TestApp', $result['app']['name']);
    }

    public function testXmlParserInvalidFile()
    {
        $this->expectException(ConfigParseException::class);

        $parser = new XmlParser();
        $parser->parse(__DIR__ . '/../config/nonexistent.xml');
    }
}