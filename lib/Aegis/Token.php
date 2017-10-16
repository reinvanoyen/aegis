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
     * @var int
     */
    private $line;

    const REGEX_T_EOL = '[\n\r]';
    const REGEX_T_OPENING_TAG = '\{'; // {
    const REGEX_T_CLOSING_TAG = '\}'; // }
    const REGEX_T_IDENT = '[a-zA-Z\-\_\/]'; // a-z A-Z - _ /
    const REGEX_T_OP = '[\+\-\*\?]'; // + - * ?
    const REGEX_T_NUMBER = '[0-9.]'; // 0 1 2 3 4 5 6 7 8 9 .
    const REGEX_T_SYMBOL = '[\(\)\,\[\]]'; // ( ) , [ ]
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

    private static $tokenTypes = [
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
     * @param int $line
     * @throws InvalidTokenType
     */
    public function __construct($type, $value, $line = 0)
    {
        if (!isset(self::$tokenTypes[$type])) {
            throw new InvalidTokenType($type);
        }

        if ($type === self::T_NUMBER) {
            $value = (float) $value;
        }

        $this->type = $type;
        $this->value = $value;
        $this->line = $line;
    }

    /**
     * Gets the name of the token
     *
     * @return string
     */
    public function getName() : string
    {
        if (isset(self::$tokenTypes[ $this->type ])) {
            return self::$tokenTypes[ $this->type ];
        }

        return 'T_UNKNOWN';
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
     * Gets the linenumber of the token
     *
     * @return int
     */
    public function getLine() : int
    {
        return $this->line;
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
        return strtoupper($this->getType().' '.$this->getValue())."\n";
    }
}
