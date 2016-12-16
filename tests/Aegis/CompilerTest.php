<?php

use Aegis\Compiler;
use Aegis\Node\RootNode;

class CompilerTest extends PHPUnit_Framework_TestCase
{
    public function testCompileShouldReturnTypeString()
    {
        $compiler = new Compiler();
        $this->assertInternalType('string', $compiler->compile(new RootNode()));
    }

    public function testCompileShouldReturnEmptyString()
    {
        $compiler = new Compiler();
        $this->assertEquals('', $compiler->compile(new RootNode()));
    }
}
