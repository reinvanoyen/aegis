<?php

namespace Aegis\Error;

use Aegis\AegisError;
use Aegis\Highlighter;
use Aegis\Token;
use Aegis\TokenStream;

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

    public function __construct(string $message, TokenStream $stream, Token $token)
    {
        parent::__construct('Syntax error on line ' . $token->getStartLine() . ' at position ' . $token->getStartPosition() . ': ' . $message);

        $this->stream = $stream;
        $this->token = $token;
    }

    /**
     * @return void
     */
    public function printExceptionDetail() : void
    {
        echo '<h3>SyntaxError</h3>';
        echo '<h4>' . $this->getMessage() . '</h4>';

        $highlighter = new Highlighter($this->stream);
        $highlighter->setErrorToken($this->token);

        echo $highlighter->getSource();
    }
}
