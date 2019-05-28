<?php

namespace Aegis\Engine;

use Aegis\Contracts\CompilerInterface;
use Aegis\Contracts\EngineInterface;
use Aegis\Contracts\LexerInterface;
use Aegis\Contracts\ParserInterface;

class Engine implements EngineInterface
{
    /**
     * @var LexerInterface $lexer
     */
    private $lexer;

    /**
     * @var ParserInterface $parser
     */
    private $parser;

    /**
     * @var CompilerInterface $compiler
     */
    private $compiler;

    /**
     * Engine constructor.
     * @param LexerInterface $lexer
     * @param ParserInterface $parser
     * @param CompilerInterface $compiler
     */
    public function __construct(LexerInterface $lexer, ParserInterface $parser, CompilerInterface $compiler)
    {
        $this->lexer = $lexer;
        $this->parser = $parser;
        $this->compiler = $compiler;
    }

    /**
     * @param string $input
     * @return string
     */
    public function evaluate(string $input): string
    {
        $tokenStream = $this->lexer->tokenize($input);
        $ast = $this->parser->parse($tokenStream);

        return $this->compiler->compile($ast);
    }

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): CompilerInterface
    {
        return $this->compiler;
    }
}
