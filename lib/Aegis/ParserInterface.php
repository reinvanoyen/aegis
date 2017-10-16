<?php

namespace Aegis;

use Aegis\Node\RootNode;

/**
 * Interface ParserInterface
 * @package Aegis
 */
interface ParserInterface
{
    /**
     * @param TokenStream $tokens
     * @return RootNode
     */
    public function parse(TokenStream $tokens) : RootNode;
}
