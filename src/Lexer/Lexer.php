<?php

namespace Aegis\Lexer;

use Aegis\Contracts\LexerInterface;
use Aegis\Token\Token;
use Aegis\Token\TokenRegexPattern;
use Aegis\Token\TokenType;

/**
 * Class Lexer
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class Lexer implements LexerInterface
{
    /**
     * @var int
     */
    private $mode;

    /**
     * @var string
     */
    private $input;

    /**
     * @var TokenStream
     */
    private $stream;

    /**
     * @var int
     */
    private $cursor;

    /**
     * @var int
     */
    private $line;

    /**
     * @var
     */
    private $lineOffset;

    /**
     * @var int
     */
    private $modeStartLine;

    /**
     * @var int
     */
    private $end;

    /**
     * @var int
     */
    private $lastCharPos;

    /**
     * @var int
     */
    private $currentChar;

    /**
     * @var string
     */
    private $currentValue;

    /**
     * @var string
     */
    private $modeStartChar;

    /**
     * @var int
     */
    private $modeStartPosition;

    /**
     * Lexer modes
     */
    const MODE_ALL = 0;
    const MODE_INSIDE_TAG = 1;
    const MODE_VAR = 2;
    const MODE_STRING = 3;
    const MODE_IDENT = 4;
    const MODE_NUMBER = 5;
    const MODE_OP = 6;

    /**
     * Tokenizes a string into a TokenStream
     *
     * @param $input
     * @return TokenStream
     * @throws \Aegis\Exception\InvalidTokenType
     */
    public function tokenize(string $input): TokenStream
    {
        $this->prepare($input);

        // Loop each character
        while ($this->cursor < $this->end) {
            $this->currentChar = $this->input[$this->cursor];

            if (preg_match('@'.TokenRegexPattern::REGEX_T_EOL.'@', $this->currentChar)) {
                if ($this->mode !== self::MODE_IDENT) {
                    ++$this->line;
                    $this->lineOffset = $this->cursor + 1;
                }
            }

            switch ($this->mode) {

                case self::MODE_ALL:
                    $this->lexAll();
                    break;
                case self::MODE_INSIDE_TAG:
                    $this->lexInsideTag();
                    break;
                case self::MODE_IDENT:
                    $this->lexIdent();
                    break;
                case self::MODE_VAR:
                    $this->lexVar();
                    break;
                case self::MODE_STRING:
                    $this->lexString();
                    break;
                case self::MODE_NUMBER:
                    $this->lexNumber();
                    break;
                case self::MODE_OP:
                    $this->lexOperator();
                    break;
            }
        }

        return $this->stream;
    }

    /**
     * Prepares the Lexer for tokenizing a string
     *
     * @param string $input
     */
    private function prepare(string $input)
    {
        $this->input = str_replace(["\n\r", "\r"], "\n", $input);

        // Create new token stream
        $this->stream = new TokenStream();

        $this->cursor = 0;
        $this->line = 1;
        $this->lineOffset = 0;
        $this->end = strlen($this->input);
        $this->lastCharPos = $this->end - 1;

        $this->currentChar = '';
        $this->currentValue = '';
        $this->modeStartChar = '';
        $this->modeStartPosition = 0;
        $this->modeStartLine = 0;

        // Set the mode to "ALL"
        $this->setMode(self::MODE_ALL);
    }

    /**
     * @throws \Aegis\Exception\InvalidTokenType
     */
    private function lexAll()
    {
        // If we are at the end of the file, write a text token with the remaining text
        if ($this->cursor + 1 === $this->end) {
            $this->stream->addToken(new Token(TokenType::T_TEXT, $this->currentValue.$this->currentChar, $this->currentValue.$this->currentChar, $this->modeStartLine, $this->line, $this->modeStartPosition, $this->getCurrentLinePosition() + 1));
            $this->advanceCursor(); // Advance one last time so the while loops stops running

            return;
        }

        if (preg_match('@'.TokenRegexPattern::REGEX_T_OPENING_TAG.'@', $this->currentChar) && preg_match('@'.TokenRegexPattern::REGEX_T_OPENING_TAG.'@', $this->getNextChar())) {

            // Add text until now to the token stream
            if ($this->currentValue !== '') {
                $this->stream->addToken(new Token(TokenType::T_TEXT, $this->currentValue, $this->currentValue, $this->modeStartLine, $this->line, $this->modeStartPosition, $this->getCurrentLinePosition()));
                $this->currentValue = '';
            }

            // Add the opening tag to the stream
            $this->stream->addToken(new Token(TokenType::T_OPENING_TAG, $this->currentChar . $this->getNextChar(), $this->currentChar . $this->getNextChar(), $this->line, $this->line, $this->getCurrentLinePosition(), $this->getCurrentLinePosition() + 2));
            $this->advanceCursor(2);
            $this->setMode(self::MODE_INSIDE_TAG);

            return;
        }

        // Write text to temp token
        $this->currentValue .= $this->currentChar;
        $this->advanceCursor();
    }

    /**
     * @throws \Aegis\Exception\InvalidTokenType
     */
    private function lexInsideTag()
    {
        if (preg_match('@'.TokenRegexPattern::REGEX_T_CLOSING_TAG.'@', $this->currentChar) && preg_match('@'.TokenRegexPattern::REGEX_T_CLOSING_TAG.'@', $this->getNextChar())) {

            // Lex closing tag

            $this->stream->addToken(new Token(TokenType::T_CLOSING_TAG, $this->currentChar . $this->getNextChar(), $this->currentChar . $this->getNextChar(), $this->line, $this->line, $this->getCurrentLinePosition(), $this->getCurrentLinePosition() + 2));
            $this->advanceCursor(2);
            $this->setMode(self::MODE_ALL);
            return;

        } elseif(preg_match('@'.TokenRegexPattern::REGEX_T_WHITESPACE.'@', $this->currentChar)) {

            // Lex whitespace

            $this->stream->addToken(new Token(TokenType::T_WHITESPACE, $this->currentChar, $this->currentChar, $this->line, $this->line, $this->getCurrentLinePosition(), $this->getCurrentLinePosition() + 1));

        } elseif (preg_match('@'.TokenRegexPattern::REGEX_T_OPENING_TAG.'@', $this->currentChar) && preg_match('@'.TokenRegexPattern::REGEX_T_OPENING_TAG.'@', $this->getNextChar())) {

            // Add text until now to the token stream

            if ($this->currentValue !== '') {
                $this->stream->addToken(new Token(TokenType::T_TEXT, $this->currentValue, $this->currentValue, $this->modeStartLine, $this->line, $this->modeStartPosition, $this->getCurrentLinePosition()));
                $this->currentValue = '';
            }

            // Add the opening tag to the stream

            $this->stream->addToken(new Token(TokenType::T_OPENING_TAG, $this->currentChar . $this->getNextChar(), $this->currentChar . $this->getNextChar(), $this->line, $this->line, $this->getCurrentLinePosition(), $this->getCurrentLinePosition() + 2));
            $this->advanceCursor(2);
            $this->setMode(self::MODE_INSIDE_TAG);
            return;

        } elseif (preg_match('@'.TokenRegexPattern::REGEX_T_IDENT.'@', $this->currentChar)) {

            // Lex identifiers

            $this->setMode(self::MODE_IDENT);
            return;

        } elseif (preg_match('@'.TokenRegexPattern::REGEX_T_STRING_DELIMITER.'@', $this->currentChar)) {

            // Lex string

            $this->modeStartChar = $this->currentChar;
            $this->setMode(self::MODE_STRING);
            $this->advanceCursor();
            return;

        } elseif (preg_match('@'.TokenRegexPattern::REGEX_T_NUMBER.'@', $this->currentChar)) {

            // Lex number

            $this->setMode(self::MODE_NUMBER);
            return;

        } elseif (preg_match('@'.TokenRegexPattern::REGEX_T_VAR_START.'@', $this->currentChar)) {

            // Lex variables

            $this->setMode(self::MODE_VAR);
            $this->advanceCursor();
            return;

        } elseif (preg_match('@'.TokenRegexPattern::REGEX_T_OP.'@', $this->currentChar)) {

            // Lex operators

            $this->setMode(self::MODE_OP);
            return;

        } elseif (preg_match('@'.TokenRegexPattern::REGEX_T_SYMBOL.'@', $this->currentChar)) {

            // Lex symbols

            $this->stream->addToken(new Token(TokenType::T_SYMBOL, $this->currentChar, $this->currentChar, $this->line, $this->line, $this->getCurrentLinePosition(), $this->getCurrentLinePosition() + 1));
        }

        $this->advanceCursor();
    }

    /**
     * @throws \Aegis\Exception\InvalidTokenType
     */
    private function lexIdent()
    {
        if (!preg_match('@'.TokenRegexPattern::REGEX_T_IDENT.'@', $this->currentChar)) {
            $this->stream->addToken(new Token(TokenType::T_IDENT, $this->currentValue, $this->currentValue, $this->line, $this->line, $this->modeStartPosition, $this->getCurrentLinePosition()));
            $this->currentValue = '';
            $this->setMode(self::MODE_INSIDE_TAG);

            return;
        }

        $this->currentValue .= $this->currentChar;
        $this->advanceCursor();
    }

    /**
     * @throws \Aegis\Exception\InvalidTokenType
     */
    private function lexString()
    {
        if ($this->currentChar === $this->modeStartChar) {
            $this->advanceCursor();
            $this->stream->addToken(new Token(TokenType::T_STRING, $this->currentValue, $this->modeStartChar.$this->currentValue.$this->modeStartChar, $this->modeStartLine, $this->line, $this->modeStartPosition, $this->getCurrentLinePosition()));
            $this->currentValue = '';
            $this->setMode(self::MODE_INSIDE_TAG);

            return;
        }

        $this->currentValue .= $this->currentChar;
        $this->advanceCursor();
    }

    /**
     * @throws \Aegis\Exception\InvalidTokenType
     */
    private function lexNumber()
    {
        if (!preg_match('@'.TokenRegexPattern::REGEX_T_NUMBER.'@', $this->currentChar)) {
            $this->stream->addToken(new Token(TokenType::T_NUMBER, $this->currentValue, $this->currentValue, $this->modeStartLine, $this->line, $this->modeStartPosition, $this->getCurrentLinePosition()));
            $this->currentValue = '';
            $this->setMode(self::MODE_INSIDE_TAG);

            return;
        }

        $this->currentValue .= $this->currentChar;
        $this->advanceCursor();
    }

    /**
     * @throws \Aegis\Exception\InvalidTokenType
     */
    private function lexVar()
    {
        if (preg_match('@'.TokenRegexPattern::REGEX_T_VAR.'@', substr($this->input, $this->cursor), $matches)) {
            $variableName = $matches[ 0 ];
            $this->stream->addToken(new Token(TokenType::T_VAR, $variableName, '@' . $variableName, $this->modeStartLine, $this->line, $this->modeStartPosition, $this->getCurrentLinePosition()));
            $this->advanceCursor(strlen($variableName));
            $this->setMode(self::MODE_INSIDE_TAG);

            return;
        }
    }

    /**
     * @throws \Aegis\Exception\InvalidTokenType
     */
    private function lexOperator()
    {
        if (!preg_match('@'.TokenRegexPattern::REGEX_T_OP.'@', $this->currentChar)) {
            $this->stream->addToken(new Token(TokenType::T_OP, $this->currentValue, $this->currentValue, $this->modeStartLine, $this->line, $this->getCurrentLinePosition(), $this->getCurrentLinePosition() + 1));
            $this->currentValue = '';
            $this->setMode(self::MODE_INSIDE_TAG);

            return;
        }

        $this->currentValue .= $this->currentChar;
        $this->advanceCursor();
    }

    /**
     * Gets the next character from the cursor position
     *
     * @return mixed
     */
    private function getNextChar(): string
    {
        return $this->input[$this->cursor + 1];
    }

    /**
     * Sets the lexing mode
     *
     * @param int $mode
     */
    private function setMode(int $mode)
    {
        $this->mode = $mode;
        $this->modeStartLine = $this->line;
        $this->modeStartPosition = $this->getCurrentLinePosition();
    }

    /**
     * Gets the position of the cursor on the current line
     *
     * @return int
     */
    private function getCurrentLinePosition(): int
    {
        return $this->cursor - $this->lineOffset;
    }

    /**
     * Advance the cursor by a given index, if no index is given, advance by 1
     *
     * @param int $index
     */
    private function advanceCursor(int $index = 1)
    {
        $this->cursor += $index;
    }
}
