<?php

declare(strict_types=1);

namespace JG\Tests\Parsers;

use PHPUnit\Framework\TestCase;
use JG\Config\Parsers\ConfParser;
use JG\Config\Exceptions\ConfigParseException;

class ConfParserTest extends TestCase
{
    public function testConfParser()
    {
        $parser = new ConfParser();
        $filePath = __DIR__ . '/../config/config.conf';

        $config = $parser->parse($filePath);

        $this->assertEquals('localhost', $config['host']);
        $this->assertEquals(3306, $config['port']);
        $this->assertEquals('root', $config['user']);
        $this->assertTrue($config['debug']);
    }

    public function testConfParserInvalidFile(): void
    {
        $this->expectException(ConfigParseException::class);
    
        $parser = new ConfParser();
        $parser->parse(__DIR__ . '/../config/nonexistent.conf');
    }
}