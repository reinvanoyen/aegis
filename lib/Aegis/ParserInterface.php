<?php

namespace Aegis;

use Aegis\Node\RootNode;

/**
 * Interface ParserInterface
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
interface ParserInterface
{
    /**
     * @param TokenStream $tokens
     * @return RootNode
     */
    public function parse(TokenStream $tokens) : RootNode;
}
