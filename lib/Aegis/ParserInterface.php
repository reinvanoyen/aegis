<?php

namespace Aegis;

interface ParserInterface
{
    public function parse(TokenStream $tokens);
}
