<?php

declare(strict_types=1);

namespace JG\Tests\Parsers;

use PHPUnit\Framework\TestCase;
use JG\Config\Parsers\IniParser;
use JG\Config\Exceptions\ConfigParseException;

class IniParserTest extends TestCase
{
    public function testIniParser()
    {
        $parser = new IniParser();
        $filePath = __DIR__ . '/../config/config.ini';

        $config = $parser->parse($filePath);

        $this->assertEquals('localhost', $config['database']['host']);
        $this->assertEquals(3306, $config['database']['port']);
        $this->assertTrue($config['app']['debug']);
    }

    public function testIniParserInvalidFile()
    {
        $this->expectException(ConfigParseException::class);

        $parser = new IniParser();
        $parser->parse(__DIR__ . '/../config/nonexistent.ini');
    }
}