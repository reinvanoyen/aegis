<?php

namespace Aegis\Token;

abstract class TokenRegexPattern
{
    const REGEX_T_EOL = '[\n\r]';
    const REGEX_T_OPENING_TAG = '\{'; // {
    const REGEX_T_CLOSING_TAG = '\}'; // }
    const REGEX_T_IDENT = '[a-zA-Z\-\_\/]'; // a-z A-Z - _ /
    const REGEX_T_OP = '[\+\-\*\?]'; // + - * ?
    const REGEX_T_NUMBER = '[0-9.]'; // 0 1 2 3 4 5 6 7 8 9 .
    const REGEX_T_SYMBOL = '[\(\)\,\[\]\$\>\<\@\#]'; // ( ) , [ ] $ > <
    const REGEX_T_VAR = '^[a-zA-Z._-]+'; // a-z A-Z _ -
    const REGEX_T_VAR_START = '\@'; // @
    const REGEX_T_STRING_DELIMITER = '[\"\']'; // " '
    const REGEX_T_WHITESPACE = '\s';
}