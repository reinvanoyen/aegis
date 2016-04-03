<?php

class Token
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

	protected static $token_names = [
		self::T_EOL => 'EOL',
		self::T_TEXT => 'TEXT',
		self::T_OPENING_TAG => 'OPENING_TAG',
		self::T_CLOSING_TAG => 'CLOSING_TAG',
		self::T_IDENT => 'IDENT',
		self::T_VAR => 'VAR',
		self::T_STRING => 'STRING',
		self::T_OP => 'OPERATOR',
	];

	public static $token_regexes = [
		self::T_EOL => '[\n\r]',
		self::T_OPENING_TAG => '{{',
		self::T_CLOSING_TAG => '}}',
		self::T_IDENT => '[a-z]',
		self::T_OP => '\+|\-',
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

	public function __toString()
	{
		return $this->getName() . '(<strong>' . $this->value . '</strong>)' . "<br />";
	}
}
