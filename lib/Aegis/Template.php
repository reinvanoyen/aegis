<?php

namespace Aegis;

use Aegis\Runtime\DefaultRuntime;
use Aegis\Helpers\File;

class Template
{
    public static $debug = true;

    public static $templateExtension = 'tpl';
    public static $outputExtension = 'php';
    public static $templateDirectory = 'templates/';
    public static $cacheDirectory = 'cache/templates/';

    private $runtime;

    public function __construct(RuntimeInterface $runtime = null)
    {
        if ($runtime) {
            $this->runtime = $runtime;
        } else {
            $this->runtime = new DefaultRuntime();
        }
    }

    public function __set($k, $v)
    {
        $this->runtime->set($k, $v);
    }

    private function getCacheFilename($filename, $part = null)
    {
        return static::$cacheDirectory.($part ? $part.'/' : '').urlencode($filename).'.'.self::$outputExtension;
    }

    private function getSourceFilename($filename)
    {
        return static::$templateDirectory.$filename.'.'.static::$templateExtension;
    }

    private function shouldRecompile($cacheFilename, $sourceFilename)
    {
        return !file_exists($cacheFilename) || filemtime($cacheFilename) <= filemtime($sourceFilename) || static::$debug;
    }

    private function createCompiler($filename)
    {
        // Get string to render
        $input = file_get_contents($this->getSourceFilename($filename));

        // Create lexer & parser
        $lexer = new Lexer();
        $parser = new Parser();

        // Create the compiler
        $compiler = new Compiler($parser->parse($lexer->tokenize($input)));

        return $compiler;
    }

    public function render($filename)
    {
        $cacheFilename = $this->getCacheFilename($filename);
        $srcFilename = $this->getSourceFilename($filename);

        if ($this->shouldRecompile($cacheFilename, $srcFilename)) {
            $compiler = $this->createCompiler($filename);
            File\write($cacheFilename, $compiler->compile());
        }

        // Execute
        return $this->execute($cacheFilename);
    }

    public function renderHead($filename)
    {
        $cacheFilename = $this->getCacheFilename($filename, 'head');
        $srcFilename = $this->getSourceFilename($filename);

        if ($this->shouldRecompile($cacheFilename, $srcFilename)) {
            $compiler = $this->createCompiler($filename);
            // Compile and save
            $compiler->compile();
            File\write($cacheFilename, $compiler->getHead());
        }

        // Execute
        return $this->execute($cacheFilename);
    }

    public function renderBody($filename)
    {
        $cacheFilename = $this->getCacheFilename($filename, 'body');
        $srcFilename = $this->getSourceFilename($filename);

        if ($this->shouldRecompile($cacheFilename, $srcFilename)) {
            $compiler = $this->createCompiler($filename);
            // Compile and save
            $compiler->compile();
            File\write($cacheFilename, $compiler->getBody());
        }

        // Execute
        return $this->execute($cacheFilename);
    }

    private function execute($filename)
    {
        ob_start();

        File\scopedRequire($filename, [
            'tpl' => $this,
            'env' => $this->runtime,
        ]);

        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    }
}

require_once __DIR__.'/Helpers/File.php';
