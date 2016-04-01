<?php

class Token
{
	public $type;
	public $value;

	const PHP_EXPR = '(?:[^\']|\\\'.*?\\\')+?';

	const T_EOF = 0;
	const T_TEXT = 2;
	const T_OPENING_TAG = 3;
	const T_CLOSING_TAG = 4;

	protected static $token_names = [
		self::T_EOF => 'EOF_TOKEN',
		self::T_TEXT => 'TEXT_TOKEN',
		self::T_OPENING_TAG => 'OPENING_TAG_TOKEN',
		self::T_CLOSING_TAG => 'CLOSING_TAG_TOKEN',
	];

	public static $token_regexes = [
		self::T_EOF => '[\n\r]',
		self::T_OPENING_TAG => '{%',
		self::T_CLOSING_TAG => '%}',
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
		return $this->getName() . '(' . $this->value . ')';
	}
}