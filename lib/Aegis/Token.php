<?php

namespace Aegis;

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

    const REGEX_T_EOL = '[\n\r]';
    const REGEX_T_OPENING_TAG = '\{'; // {
    const REGEX_T_CLOSING_TAG = '\}'; // }
    const REGEX_T_IDENT = '[a-zA-Z\-\_\/]'; // a-z A-Z - _ /
    const REGEX_T_OP = '[\+\-\*\?]'; // + - * ?
    const REGEX_T_NUMBER = '[0-9.]'; // 0 1 2 3 4 5 6 7 8 9 .
    const REGEX_T_SYMBOL = '[\(\)\,\[\]\$\>\<]'; // ( ) , [ ] $ > <
    const REGEX_T_VAR = '^[a-zA-Z._-]+'; // a-z A-Z _ -
    const REGEX_T_VAR_START = '\@'; // @
    const REGEX_T_STRING_DELIMITER = '[\"\']'; // " '

    const T_TEXT = 0;
    const T_OPENING_TAG = 1;
    const T_CLOSING_TAG = 2;
    const T_IDENT = 3;
    const T_VAR = 4;
    const T_STRING = 5;
    const T_OP = 6;
    const T_NUMBER = 7;
    const T_SYMBOL = 8;

    private static $tokenTypeNames = [
        self::T_TEXT => 'T_TEXT',
        self::T_OPENING_TAG => 'T_OPENING_TAG',
        self::T_CLOSING_TAG => 'T_CLOSING_TAG',
        self::T_IDENT => 'T_IDENT',
        self::T_VAR => 'T_VAR',
        self::T_STRING => 'T_STRING',
        self::T_OP => 'T_OP',
        self::T_NUMBER => 'T_NUMBER',
        self::T_SYMBOL => 'T_SYMBOL',
    ];

    /**
     * Token constructor.
     * @param $type
     * @param $value
     * @param string $source
     * @param int $line
     * @throws InvalidTokenType
     */
    public function __construct(int $type, string $value, string $source = '', int $startLine = 0, int $endLine = 0, int $startPosition = 0, int $endPosition = 0)
    {
        if (!isset(self::$tokenTypeNames[$type])) {
            throw new InvalidTokenType($type);
        }

        if ($type === self::T_NUMBER) {
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
    public function getName() : string
    {
    	return self::getNameByType($this->type);
    }

    /**
     * Gets the type of the token
     *
     * @return int
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * Gets the starting linenumber of the token
     *
     * @return int
     */
    public function getStartLine() : int
    {
        return $this->startLine;
    }

    /**
     * Gets the ending linenumber of the token
     *
     * @return int
     */
    public function getEndLine() : int
    {
        return $this->endLine;
    }

    /**
     * @return bool
     */
    public function isMultiline() : bool
    {
        return ($this->getStartLine() !== $this->getEndLine());
    }

    /**
     * Gets the start position of the token on the line
     *
     * @return int
     */
    public function getStartPosition() : int
    {
        return $this->startPosition;
    }

    /**
     * Gets the end position of the token on the line
     *
     * @return int
     */
    public function getEndPosition() : int
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
    public function __toString() : string
    {
        if (
            $this->getType() !== self::T_TEXT &&
            $this->getType() !== self::T_OPENING_TAG &&
            $this->getType() !== self::T_CLOSING_TAG
        ) {
            return self::getStringRepresentation($this->getType(), $this->getValue());
        }

        return $this->getName();
    }

    /**
     * @return string
     */
    public function getSource() : string
    {
        return $this->source ?: $this->getValue();
    }

	/**
	 * @param int $type
	 * @return StringNode
	 */
	public static function getNameByType(int $type) : string
	{
		if (isset(self::$tokenTypeNames[ $type ])) {
			return self::$tokenTypeNames[ $type ];
		}

		return 'T_UNKNOWN';
	}

	/**
	 * @param int $type
	 * @param string $value
	 * @return string
	 */
	public static function getStringRepresentation(int $type, $value = '') : string
	{
		return self::getNameByType($type) . '(' . $value . ')';
	}
}
