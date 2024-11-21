<?php

declare(strict_types=1);

namespace JG\Tests\Parsers;

use PHPUnit\Framework\TestCase;
use JG\Config\Parsers\PhpParser;
use JG\Config\Exceptions\ConfigParseException;

class PhpParserTest extends TestCase
{
    public function testPhpParser()
    {
        $parser = new PhpParser();
        $filePath = __DIR__ . '/../config/config.php';
        
        $config = $parser->parse($filePath);

        $this->assertEquals('localhost', $config['database']['host']);
        $this->assertEquals(3306, $config['database']['port']);
        $this->assertTrue($config['app']['debug']);
        $this->assertNull($config['app']['cache']);
    }

    public function testPhpParserInvalidFile()
    {
        $this->expectException(ConfigParseException::class);

        $parser = new PhpParser();
        $parser->parse(__DIR__ . '/../config/nonexistent.php');
    }
}