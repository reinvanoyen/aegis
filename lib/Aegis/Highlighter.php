<?php

namespace Aegis;

/**
 * Class Highlighter
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class Highlighter
{
    /**
     * @var TokenStream
     */
    private $stream;

    /**
     * @var Token
     */
    private $errorToken;

    /**
     * @var array
     */
    private static $tokenHighlightColors = [
        Token::T_TEXT => '#777',
        Token::T_OPENING_TAG => '#8a8a8a',
        Token::T_CLOSING_TAG => '#8a8a8a',
        Token::T_IDENT => '#e0702c',
        Token::T_VAR => '#5e9ad6',
        Token::T_STRING => '#f9de0c',
        Token::T_OP => '#ffffff',
        Token::T_NUMBER => '#8ed15e',
        Token::T_SYMBOL => '#e0702c',
    ];

    /**
     * Highlighter constructor.
     * @param TokenStream $stream
     */
    public function __construct(TokenStream $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Gets the highlight color for a token
     *
     * @param Token $token
     * @return string
     */
    private function getHighlightColor(Token $token) : string
    {
        return self::$tokenHighlightColors[ $token->getType() ];
    }

    /**
     * @param Token $token
     * @param false $string
     * @return string
     */
    private function getHighlightedStringForToken(Token $token, $string = false) : string
    {
        $source = htmlspecialchars(($string !== false ? $string : $token->getSource()));

        if ($this->errorToken && $this->isSameToken($this->errorToken, $token)) {
            return '<span style="background-color: red; color: white;">' . $source . '</span>';
        }
        return '<span style="color: ' . $this->getHighlightColor($token) . ';">' . $source . '</span>';
    }

    /**
     * @param Token $token
     * @return void
     */
    public function setErrorToken(Token $token) : void
    {
        $this->errorToken = $token;
    }

    public function isSameToken(Token $token, Token $compareWith)
    {
        return ($token->getStartLine() === $compareWith->getStartLine() && $token->getStartPosition() === $compareWith->getStartPosition());
    }

    /**
     * @return string
     */
    public function getSource() : string
    {
        $currentLine = 0;
        $highlightedSource = '';

        foreach ($this->stream->getTokens() as $token) {
            if ($token->getStartLine() !== $currentLine) {
                $currentLine = $token->getStartLine();
                $highlightedSource .= '<strong style="display: inline-block; width: 20px; text-align: right; padding-right: 15px;">' . $currentLine . '</strong>';
            }

            if ($token->isMultiline()) {
                $tokenLines = explode("\n", $token->getSource());
                $tokenLineCount = count($tokenLines);

                foreach ($tokenLines as $index => $tokenLine) {
                    if ($token->getStartLine() !== $currentLine) {
                        $highlightedSource .= '<strong style="display: inline-block; width: 20px; text-align: right; padding-right: 15px;">' . $currentLine . '</strong>';
                    }

                    $highlightedSource .= $this->getHighlightedStringForToken($token, $tokenLine);

                    if ($index < $tokenLineCount - 1) {
                        $highlightedSource .= '<span style="color: #555555;">↵</span><br />';
                        $currentLine++;
                    }
                }
            } else {
                $highlightedSource .= $this->getHighlightedStringForToken($token) . ' ';
            }
        }

        return '<pre style="background-color: #333; color: #d9d9d9; padding: 5px;">' . $highlightedSource . '</pre>';
    }
}