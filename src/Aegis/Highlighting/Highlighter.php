<?php

namespace Aegis\Highlighting;

use Aegis\Token\Token;
use Aegis\Lexer\TokenStream;

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
     * Highlighter constructor.
     * @param TokenStream $stream
     * @param HighlightColor $color
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
    private function getHighlightColor(Token $token): string
    {
        return constant(HighlightColor::class.'::COLOR_'.$token->getName());
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
            return '<span style="padding: 0 5px; border-radius: 5px; background-color: '.HighlightColor::COLOR_ERROR_BG.'; color: '.HighlightColor::COLOR_ERROR_FG.';">' . $source . '</span>';
        }

        return '<span style="color: '.$this->getHighlightColor($token).';">'.str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $source).'</span>';
    }

    /**
     * @param Token $token
     * @return void
     */
    public function setErrorToken(Token $token): void
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
    public function getSource(): string
    {
        $currentLine = 0;
        $highlightedSource = '';

        foreach ($this->stream->getTokens() as $token) {
            if ($token->getStartLine() !== $currentLine) {
                $highlightedSource .= '<div'.($currentLine === $this->errorToken->getStartLine() ? ' style="background-color: rgba(41, 98, 255, .3);"' : '').'>'; // Start of line
                $currentLine = $token->getStartLine();
                $highlightedSource .= '<strong style="display: inline-block; width: 20px; text-align: right; padding-right: 15px;">' . $currentLine . '</strong>';
            }

            if ($token->isMultiline()) {
                $tokenLines = explode("\n", $token->getSource());
                $tokenLineCount = count($tokenLines);

                foreach ($tokenLines as $index => $tokenLine) {
                    if ($token->getStartLine() !== $currentLine) {
                        $highlightedSource .= '<div'.($currentLine === $this->errorToken->getStartLine() ? ' style="background-color: '.HighlightColor::COLOR_ERROR_LINE_BG.';"' : '').'>'; // Start of line
                        $highlightedSource .= '<strong style="display: inline-block; width: 20px; text-align: right; padding-right: 15px;">' . $currentLine . '</strong>';
                    }

                    $highlightedSource .= $this->getHighlightedStringForToken($token, $tokenLine);

                    if ($index < $tokenLineCount - 1) {
                        $highlightedSource .= '<span style="color: #555555;">↵</span>';
                        $highlightedSource .= '</div>'; // End of line
                        $currentLine++;
                    }
                }
            } else {
                $highlightedSource .= $this->getHighlightedStringForToken($token) . ' ';
            }
        }

        $htmlWrapper = '<div style="background-color: '.HighlightColor::COLOR_BG.'; color: '.HighlightColor::COLOR_FG.'; padding: 5px; border-radius: 5px; font-size: 13px; font-family: monospace;">';
        $htmlWrapper .= $highlightedSource;
        $htmlWrapper .= '</div>';

        return $htmlWrapper;
    }
}
