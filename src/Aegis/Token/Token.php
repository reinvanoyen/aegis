<?php

namespace Aegis\Token;

use Aegis\Exception\InvalidTokenType;

/**
 * Class Token
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
final class Token
{
    /**
     * @var int
     */
    private $type;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $source;

    /**
     * @var int
     */
    private $startLine;

    /**
     * @var int
     */
    private $endLine;

    /**
     * @var int
     */
    private $startPosition;

    /**
     * @var int
     */
    private $endPosition;

    /**
     * Token constructor.
     * @param $type
     * @param $value
     * @param string $source
     * @param int $startLine
     * @param int $endLine
     * @param int $startPosition
     * @param int $endPosition
     * @throws InvalidTokenType
     */
    public function __construct(int $type, string $value, string $source = '', int $startLine = 0, int $endLine = 0, int $startPosition = 0, int $endPosition = 0)
    {
        if (!isset(TokenType::TOKEN_TYPE_NAMES[$type])) {
            throw new InvalidTokenType($type);
        }

        if ($type === TokenType::T_NUMBER) {
            $value = (float) $value;
        }

        $this->type = $type;
        $this->value = $value;
        $this->source = $source;
        $this->startLine = $startLine;
        $this->endLine = $endLine;
        $this->startPosition = $startPosition;
        $this->endPosition = $endPosition;
    }

    /**
     * Gets the name of the token
     *
     * @return string
     */
    public function getName(): string
    {
        if (isset(TokenType::TOKEN_TYPE_NAMES[$this->type])) {
            return TokenType::TOKEN_TYPE_NAMES[$this->type];
        }

        return 'T_UNKNOWN';
    }

    /**
     * Gets the type of the token
     *
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Gets the starting linenumber of the token
     *
     * @return int
     */
    public function getStartLine(): int
    {
        return $this->startLine;
    }

    /**
     * Gets the ending linenumber of the token
     *
     * @return int
     */
    public function getEndLine(): int
    {
        return $this->endLine;
    }

    /**
     * @return bool
     */
    public function isMultiline(): bool
    {
        return ($this->getStartLine() !== $this->getEndLine());
    }

    /**
     * Gets the start position of the token on the line
     *
     * @return int
     */
    public function getStartPosition(): int
    {
        return $this->startPosition;
    }

    /**
     * Gets the end position of the token on the line
     *
     * @return int
     */
    public function getEndPosition(): int
    {
        return $this->endPosition;
    }

    /**
     * Gets the value of the token
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Gets a string representation of the node
     *
     * @return string
     */
    public function __toString(): string
    {
        if (
            $this->getType() !== TokenType::T_TEXT &&
            $this->getType() !== TokenType::T_OPENING_TAG &&
            $this->getType() !== TokenType::T_CLOSING_TAG
        ) {
            return $this->getStringRepresentation();
        }

        return $this->getName();
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source ?: $this->getValue();
    }

    /**
     * @return string
     */
    public function getStringRepresentation(): string
    {
        return $this->getName().'('.$this->getValue().')';
    }
}
