<?php

require_once 'Lexer.php';
require_once 'TokenStream.php';

class SimpleLexer extends Lexer
{
	private $mode;

	private $input;

	private $cursor;
	private $end;
	private $token_starts;

	private $token_stream;

	const MODE_ALL = 0;

	public function __construct()
	{
		define( 'REGEX_TOKEN_START', '@(' . Token::$token_regexes[ Token::T_OPENING_TAG ] . ')|(' . Token::$token_regexes[ Token::T_CLOSING_TAG ] . ')@s' );
	}

	public function tokenize( $input )
	{
		// Create new token stream
		$this->token_stream = new TokenStream();

		// Set input
		$this->input = str_replace( [ "\n\r", "\r" ], "\n", $input );

		// Set mode
		$this->mode = self::MODE_ALL;

		// Set start position
		$this->setCursor( 0 );

		// Set end position
		$this->end = strlen( $this->input );

		// Find positions of tags
		preg_match_all( REGEX_TOKEN_START, $this->input, $m, PREG_OFFSET_CAPTURE );
		$this->token_starts = $m;

		// Loop each character
		while( $this->cursor < $this->end )
		{
			switch( $this->mode )
			{
				case self::MODE_ALL:
					$this->all();
					break;
			}

			// Advance the cursor
			$this->cursor++;
		}

		return $this->token_stream;
	}

	private function all()
	{
		$next_token_offset = $this->findNextTokenOffset();
		$text_token_value = substr( $this->input, $this->cursor, $next_token_offset );
		$text_token = new Token( Token::T_TEXT, $text_token_value );

		$this->advanceCursor( strlen( $text_token_value ) );

		$this->token_stream->addToken( $text_token );
	}

	private function findNextTokenOffset()
	{
		foreach( $this->token_starts[ 0 ] as $t )
		{
			if( isset( $t[ 1 ] ) )
			{
				$offset = $t[ 1 ] - 1;

				if( $offset > $this->cursor )
				{
					return $offset;
				}
			}
		}
	}

	private function setCursorAtNextToken()
	{
		$this->setCursor( $this->findNextTokenOffset() );
	}

	private function setCursor( $n )
	{
		$this->cursor = $n;
	}

	private function advanceCursor( $n )
	{
		$this->cursor += $n;
	}
}