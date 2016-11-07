<?php

namespace Aegis;

class Lexer implements LexerInterface
{
    private $mode;

    private $input;
    private $tokenStream;

    private $cursor = 0;
    private $line = 1;
    private $end;

    private $currentChar;
    private $lastCharPos;
    private $currentValue = '';
    private $modeStartChar;

    const MODE_ALL = 0;
    const MODE_INSIDE_TAG = 1;
    const MODE_VAR = 2;
    const MODE_STRING = 3;
    const MODE_IDENT = 4;
    const MODE_NUMBER = 5;
    const MODE_OP = 6;

    public function tokenize($input)
    {
        // Create new token stream
        $this->tokenStream = new TokenStream();

        // Set input
        $this->input = str_replace(["\n\r", "\r"], "\n", $input);

        // Set mode
        $this->setMode(self::MODE_ALL);

        // Set end position
        $this->end = strlen($this->input);

        $this->lastCharPos = $this->end - 1;

        // Loop each character
        while ($this->cursor < $this->end) {
            $this->currentChar = $this->getCharAtCursor();

            if (preg_match('@'.Token::REGEX_T_EOL.'@', $this->currentChar)) {
                ++$this->line;
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

        return $this->tokenStream;
    }

    private function lexAll()
    {
        // If were at the end of the file, write a text token with the remaining text
        if ($this->cursor + 1 === $this->end) {
            $this->tokenStream->addToken(new Token(Token::T_TEXT, $this->currentValue.$this->currentChar, $this->line));
            $this->advanceCursor(); // Advance one last time so the while loops stops running

            return;
        }

        if ($this->currentChar === '{' && $this->getNextChar() === '{') {

            // Add text until now to the token stream
            if ($this->currentValue !== '') {
                $this->tokenStream->addToken(new Token(Token::T_TEXT, $this->currentValue, $this->line));
                $this->currentValue = '';
            }

            // Add the opening tag to the stream
            $this->tokenStream->addToken(new Token(Token::T_OPENING_TAG, '{{', $this->line));
            $this->advanceCursor(2);
            $this->setMode(self::MODE_INSIDE_TAG);

            return;
        }

        // Write text to temp token
        $this->currentValue .= $this->currentChar;
        $this->advanceCursor();
    }

    private function lexInsideTag()
    {
        if ($this->currentChar === '}' && $this->getNextChar() === '}') {
            $this->tokenStream->addToken(new Token(Token::T_CLOSING_TAG, '}}', $this->line));
            $this->advanceCursor(2);

            $this->setMode(self::MODE_ALL);

            return;
        } elseif (
            $this->currentChar === '/' && preg_match('@'.Token::REGEX_T_IDENT.'@', $this->getNextChar()) ||
            preg_match('@'.Token::REGEX_T_IDENT.'@', $this->currentChar)
        ) {
            $this->setMode(self::MODE_IDENT);

            return;
        } elseif ($this->currentChar === '"' || $this->currentChar === '\'') {
            $this->modeStartChar = $this->currentChar;
            $this->setMode(self::MODE_STRING);
            $this->advanceCursor();

            return;
        } elseif (preg_match('@'.Token::REGEX_T_NUMBER.'@', $this->currentChar)) {
            $this->setMode(self::MODE_NUMBER);

            return;
        } elseif ($this->currentChar === '@') {
            $this->setMode(self::MODE_VAR);
            $this->advanceCursor();

            return;
        } elseif (preg_match('@'.Token::REGEX_T_OP.'@', $this->currentChar)) {
            $this->setMode(self::MODE_OP);

            return;
        } elseif (preg_match('@'.Token::REGEX_T_SYMBOL.'@', $this->currentChar)) {
            $this->tokenStream->addToken(new Token(Token::T_SYMBOL, $this->currentChar, $this->line));
        }

        $this->advanceCursor();
    }

    // MODE IDENT
    private function lexIdent()
    {
        if ($this->currentChar !== '/' && !preg_match('@'.Token::REGEX_T_IDENT.'@', $this->currentChar)) {
            $this->tokenStream->addToken(new Token(Token::T_IDENT, $this->currentValue, $this->line));
            $this->currentValue = '';
            $this->setMode(self::MODE_INSIDE_TAG);

            return;
        }

        $this->currentValue .= $this->currentChar;
        $this->advanceCursor();
    }

    // MODE STRING
    private function lexString()
    {
        if ($this->currentChar === $this->modeStartChar) {
            $this->advanceCursor();
            $this->tokenStream->addToken(new Token(Token::T_STRING, $this->currentValue, $this->line));
            $this->currentValue = '';
            $this->setMode(self::MODE_INSIDE_TAG);

            return;
        }

        $this->currentValue .= $this->currentChar;
        $this->advanceCursor();
    }

    // MODE NUMBER
    private function lexNumber()
    {
        if (!preg_match('@'.Token::REGEX_T_NUMBER.'@', $this->currentChar)) {
            $this->tokenStream->addToken(new Token(Token::T_NUMBER, $this->currentValue, $this->line));
            $this->currentValue = '';
            $this->setMode(self::MODE_INSIDE_TAG);

            return;
        }

        $this->currentValue .= $this->currentChar;
        $this->advanceCursor();
    }

    // MODE VAR
    private function lexVar()
    {
        if (preg_match('@'.Token::REGEX_T_VAR.'@', substr($this->input, $this->cursor), $matches)) {
            $variableName = $matches[ 0 ];
            $this->tokenStream->addToken(new Token(Token::T_VAR, $variableName, $this->line));
            $this->advanceCursor(strlen($variableName));
            $this->setMode(self::MODE_INSIDE_TAG);

            return;
        }
    }

    // MODE OPERATOR
    private function lexOperator()
    {
        if (!preg_match('@'.Token::REGEX_T_OP.'@', $this->currentChar)) {
            $this->tokenStream->addToken(new Token(Token::T_OP, $this->currentValue, $this->line));
            $this->currentValue = '';
            $this->setMode(self::MODE_INSIDE_TAG);

            return;
        }

        $this->currentValue .= $this->currentChar;
        $this->advanceCursor();
    }

    private function getCharAtCursor()
    {
        return $this->input[$this->cursor];
    }

    private function getNextChar()
    {
        return $this->input[$this->cursor + 1];
    }

    private function setMode($mode)
    {
        $this->mode = $mode;
    }

    private function setCursor($n)
    {
        $this->cursor = $n;
    }

    private function advanceCursor($n = 1)
    {
    	$this->setCursor($this->cursor + $n);
    }
}
