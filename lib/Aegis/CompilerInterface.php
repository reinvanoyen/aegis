<?php

namespace Aegis;

/**
 * Interface CompilerInterface
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
interface CompilerInterface
{
    /**
     * @param Node $node
     * @return string
     */
    public function compile(Node $node) : string;

    /**
     * @param string $string
     * @return void
     */
    public function write(string $string) : void;
}
