<?php

namespace Aegis\Token;

abstract class TokenType
{
    const T_TEXT = 0;
    const T_OPENING_TAG = 1;
    const T_CLOSING_TAG = 2;
    const T_IDENT = 3;
    const T_VAR = 4;
    const T_STRING = 5;
    const T_OP = 6;
    const T_NUMBER = 7;
    const T_SYMBOL = 8;
    const T_WHITESPACE = 9;

    const TOKEN_TYPE_NAMES = [
        self::T_TEXT => 'T_TEXT',
        self::T_OPENING_TAG => 'T_OPENING_TAG',
        self::T_CLOSING_TAG => 'T_CLOSING_TAG',
        self::T_IDENT => 'T_IDENT',
        self::T_VAR => 'T_VAR',
        self::T_STRING => 'T_STRING',
        self::T_OP => 'T_OP',
        self::T_NUMBER => 'T_NUMBER',
        self::T_SYMBOL => 'T_SYMBOL',
        self::T_WHITESPACE => 'T_WHITESPACE',
    ];
}
