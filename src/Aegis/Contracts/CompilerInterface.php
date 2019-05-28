<?php

namespace Aegis\Contracts;

use Aegis\Compiler\Compiler;
use Aegis\Node\Node;

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
    public function compile(Node $node): string;

    /**
     * @param string $string
     * @return void
     */
    public function write(string $string): void;

    /**
     * @return Compiler
     */
    public function clone(): Compiler;
}
