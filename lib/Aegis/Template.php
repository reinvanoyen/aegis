<?php

namespace Aegis;

use Aegis\Helpers\File;

class Template
{
    public static $debug = true;

    public static $templateExtension = 'tpl';
    public static $outputExtension = 'php';
    public static $templateDirectory = 'templates/';
    public static $cacheDirectory = 'cache/templates/';

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

    private function compileFromFilename($filename)
    {
	    $input = file_get_contents($this->getSourceFilename($filename));
	    return $this->compile($input);
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

    public function render($filename)
    {
        $cacheFilename = $this->getCacheFilename($filename);
        $srcFilename = $this->getSourceFilename($filename);

        if ($this->shouldRecompile($cacheFilename, $srcFilename)) {

            File\write($cacheFilename, $this->compileFromFilename($filename));
        }

        // Execute
        return $this->execute($cacheFilename);
    }

    public function renderHead($filename)
    {
        $cacheFilename = $this->getCacheFilename($filename, 'head');
        $srcFilename = $this->getSourceFilename($filename);

        if ($this->shouldRecompile($cacheFilename, $srcFilename)) {

	        $this->compileFromFilename($filename);
            File\write($cacheFilename, $this->compiler->getHead());
        }

        // Execute
        return $this->execute($cacheFilename);
    }

    public function renderBody($filename)
    {
        $cacheFilename = $this->getCacheFilename($filename, 'body');
        $srcFilename = $this->getSourceFilename($filename);

        if ($this->shouldRecompile($cacheFilename, $srcFilename)) {

            $this->compileFromFilename($filename);
            File\write($cacheFilename, $this->compiler->getBody());
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