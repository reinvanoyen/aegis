<?php

namespace Aegis\Contracts;

interface EngineInterface
{
    /**
     * @param string $input
     * @return string
     */
    public function evaluate(string $input): string;

    /**
     * @return LexerInterface
     */
    public function getLexer(): LexerInterface;

    /**
     * @return ParserInterface
     */
    public function getParser(): ParserInterface;

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): CompilerInterface;
}
