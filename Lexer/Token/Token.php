<?php

class Token implements \JsonSerializable
{
	public $type;
	public $value;

	const PHP_EXPR = '(?:[^\']|\\\'.*?\\\')+?';

	const T_EOL = 0;
	const T_TEXT = 1;
	const T_OPENING_TAG = 2;
	const T_CLOSING_TAG = 3;
	const T_IDENT = 4;
	const T_VAR = 5;
	const T_STRING = 6;
	const T_OP = 7;
	const T_NUMBER = 8;

	const REGEX_T_EOL = '[\n\r]';
	const REGEX_T_OPENING_TAG = '{{';
	const REGEX_T_CLOSING_TAG = '}}';
	const REGEX_T_IDENT = '[a-zA-Z]';
	const REGEX_T_VAR = '^[a-zA-Z._-]+';
	const REGEX_T_OP = '\+|\-|\=|\!';
	const REGEX_T_NUMBER = '[0-9.]';

	private static $token_names = [
		self::T_EOL => 'T_EOL',
		self::T_TEXT => 'T_TEXT',
		self::T_OPENING_TAG => 'T_OPENING_TAG',
		self::T_CLOSING_TAG => 'T_CLOSING_TAG',
		self::T_IDENT => 'T_IDENT',
		self::T_VAR => 'T_VAR',
		self::T_STRING => 'T_STRING',
		self::T_OP => 'T_OP',
		self::T_NUMBER => 'T_NUMBER',
	];

	public function __construct( $type, $value )
	{
		$this->type = $type;
		$this->value = $value;
	}

	public function getName()
	{
		if( isset( self::$token_names[ $this->type ] ) )
		{
			return self::$token_names[ $this->type ];
		}

		return 'T_UNKNOWN';
	}

	public function getType()
	{
		return $this->type;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function __toString()
	{
		return $this->getName() . '(' . $this->value . ')' . "\n";
	}

	public function jsonSerialize()
	{
		return [
			'type' => $this->getName(),
			'value' => $this->getValue(),
		];
	}
}
