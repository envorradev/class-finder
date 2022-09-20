<?php

namespace Envorra\ClassFinder;

use SplFileInfo;
use PhpParser\Parser;
use PhpParser\ParserFactory;

/**
 * CodeParser
 *
 * @package Envorra\ClassFinder
 */
class CodeParser
{
    protected static ?Parser $instance = null;

    /**
     * @param  string  $code
     * @return array
     */
    public static function parse(string $code): array
    {
        return static::parser()->parse($code);
    }

    /**
     * @param  SplFileInfo  $file
     * @return array
     */
    public static function parseFile(SplFileInfo $file): array
    {
        return static::parse($file->openFile()->fread($file->getSize()));
    }

    /**
     * @return Parser
     */
    public static function parser(): Parser
    {
        static::$instance ??= (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        return static::$instance;
    }
}
