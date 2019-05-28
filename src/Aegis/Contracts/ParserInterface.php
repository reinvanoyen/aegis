<?php

namespace Aegis\Contracts;

use Aegis\Lexer\TokenStream;
use Aegis\Parser\AbstractSyntaxTree;

/**
 * Interface ParserInterface
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
interface ParserInterface
{
    public function __construct(NodeCollectionInterface $nodeCollection);

    /**
     * @param TokenStream $tokens
     * @return AbstractSyntaxTree
     */
    public function parse(TokenStream $tokens): AbstractSyntaxTree;
}
