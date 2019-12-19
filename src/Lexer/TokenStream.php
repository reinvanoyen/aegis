<?php

namespace Aegis\Lexer;

use Aegis\Exception\NoTokenAtIndex;
use Aegis\Token\Token;

/**
 * Class TokenStream
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class TokenStream
{
    /**
     * @var Token[]
     */
    private $tokens = [];

    /**
     * Adds a token to the TokenStream
     *
     * @param Token $token
     */
    public function addToken(Token $token): void
    {
        $this->tokens[] = $token;
    }

    /**
     * Gets a token at given index
     *
     * @param $index
     * @return Token
     * @throws NoTokenAtIndex
     */
    public function getToken($index): Token
    {
        if (!isset($this->tokens[$index])) {
            throw new NoTokenAtIndex($index);
        }

        return $this->tokens[$index];
    }

    /**
     * Gets all tokens
     *
     * @return Token[]
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $output = '<pre>';

        foreach ($this->getTokens() as $token) {
            $output .= '<div style="padding: 3px 0; border-bottom: 1px solid #ccc;">';
            $output .= '<strong style="color: blue; padding-right: 10px;">' . htmlspecialchars($token->getValue()) . '</strong>';
            $output .= '<span style="color: #555; padding-right: 10px;">' . $token->getName() . '</span>';
            $output .= '<span style="color: red;">line: ' . $token->getStartLine() . '-' . $token->getEndLine() . ', position: ' . $token->getStartPosition() . '-' . $token->getEndPosition() . '</span>';
            $output .= '</div>';
        }

        $output .= '</pre>';

        return $output;
    }
}
