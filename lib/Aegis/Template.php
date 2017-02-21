<?php

namespace Aegis;

use Aegis\Cache\Filesystem;
use Aegis\Helpers\File as FileUtil;

class Template
{
    public static $debug = true;

    public static $templateExtension = 'tpl';
    public static $templateDirectory = 'templates/';

    private $runtime;
    private $parser;
    private $lexer;
    private $compiler;

    public function __construct(RuntimeInterface $runtime)
    {
        $this->runtime = $runtime;
    }

    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;
        $this->parser->setRuntime($this->runtime);
    }

    public function setLexer(LexerInterface $lexer)
    {
        $this->lexer = $lexer;
    }

    public function setCompiler(CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }

    public function __set($k, $v)
    {
        $this->runtime->set($k, $v);
    }

    private function compile($input)
    {
        if (!$this->lexer) {
            throw new AegisError('Lexer needs to be set before compiling');
        }

        if (!$this->parser) {
            throw new AegisError('Parser needs to be set before compiling');
        }

        if (!$this->compiler) {
            throw new AegisError('Compiler needs to be set before compiling');
        }

        $tokenStream = $this->lexer->tokenize($input);
        $rootNode = $this->parser->parse($tokenStream);

        return $this->compiler->compile($rootNode);
    }

    private function compileFromFilename($filename)
    {
        $input = file_get_contents($filename);
        return $this->compile($input);
    }

    private function getSourceFilename($filename)
    {
        return static::$templateDirectory.$filename.'.'.static::$templateExtension;
    }

    public function render($filename)
    {
        $filename = $this->getSourceFilename($filename);
        $file = Filesystem::load($filename);

        if ($file->getTimestamp() <= filemtime($filename) || static::$debug) {
            $file->write($this->compileFromFilename($filename));
        }

        return $this->execute($file->getFilename());
    }

    public function renderHead($filename)
    {
        $filename = $this->getSourceFilename($filename);
        $file = Filesystem::load($filename, 'head');

        if ($file->getTimestamp() <= filemtime($filename) || static::$debug) {
            $this->compileFromFilename($filename);
            $file->write($this->compiler->getHead());
        }

        return $this->execute($file->getFilename());
    }

    public function renderBody($filename)
    {
        $filename = $this->getSourceFilename($filename);
        $file = Filesystem::load($filename, 'body');

        if ($file->getTimestamp() <= filemtime($filename) || static::$debug) {
            $this->compileFromFilename($filename);
            $file->write($this->compiler->getBody());
        }

        return $this->execute($file->getFilename());
    }

    private function execute($filename)
    {
        ob_start();

        FileUtil\scopedRequire($filename, [
            'tpl' => $this,
            'env' => $this->runtime,
        ]);

        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    }
}

require_once __DIR__.'/Helpers/File.php';