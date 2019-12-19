<?php

namespace Aegis\Exception;

use Aegis\Highlighting\Highlighter;
use Aegis\Lexer\TokenStream;
use Aegis\Token\Token;

/**
 * Class SyntaxError
 * @package Aegis\Error
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class SyntaxError extends AegisError
{
    /**
     * @var TokenStream
     */
    private $stream;

    /**
     * @var Token
     */
    private $token;

    /**
     * SyntaxError constructor.
     * @param string $message
     * @param TokenStream $stream
     * @param Token $token
     */
    public function __construct(string $message, TokenStream $stream, Token $token)
    {
        parent::__construct('Syntax error on line '.$token->getStartLine().' at position '.$token->getStartPosition().': '.$message);

        $this->stream = $stream;
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getExceptionDetail(): string
    {
        $highlighter = new Highlighter($this->stream);
        $highlighter->setErrorToken($this->token);

        return $highlighter->getSource();
    }
}
