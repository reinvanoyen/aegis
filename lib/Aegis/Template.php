<?php

namespace Aegis;

use Aegis\Cache\Filesystem;
use Aegis\Helpers\File as FileUtil;

/**
 * Class Template
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class Template
{
    /**
     * @var bool
     */
    public static $debug = true;

    /**
     * @var string
     */
    public static $templateExtension = 'tpl';

    /**
     * @var string
     */
    public static $templateDirectory = 'templates/';

    /**
     * @var RuntimeInterface
     */
    private $runtime;

    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var LexerInterface
     */
    private $lexer;

    /**
     * @var CompilerInterface
     */
    private $compiler;

    public function __construct(RuntimeInterface $runtime)
    {
        $this->runtime = $runtime;
    }

    /**
     * @param ParserInterface $parser
     */
    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;
        $this->parser->setRuntime($this->runtime);
    }

    /**
     * @param LexerInterface $lexer
     */
    public function setLexer(LexerInterface $lexer)
    {
        $this->lexer = $lexer;
    }

    /**
     * @param CompilerInterface $compiler
     */
    public function setCompiler(CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->runtime->set($key, $value);
    }

    /**
     * @param $input
     * @return mixed
     * @throws AegisError
     */
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

    /**
     * @param $filename
     * @return mixed
     */
    private function compileFromFilename($filename)
    {
        $input = file_get_contents($filename);
        return $this->compile($input);
    }

    /**
     * @param $filename
     * @return string
     */
    private function getSourceFilename($filename)
    {
        return static::$templateDirectory.$filename.'.'.static::$templateExtension;
    }

    /**
     * @param $filename
     * @return string
     */
    public function render($filename)
    {
        $filename = $this->getSourceFilename($filename);
        $file = Filesystem::load($filename);

        if ($file->getTimestamp() <= filemtime($filename) || static::$debug) {
            $file->write($this->compileFromFilename($filename));
        }

        return $this->execute($file->getFilename());
    }

    /**
     * @param $filename
     * @return string
     */
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

    /**
     * @param $filename
     * @return string
     */
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

    /**
     * @param $filename
     * @return string
     */
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
