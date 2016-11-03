<?php

use Aegis\Compiler;
use Aegis\Node\RootNode;

class CompilerTest extends PHPUnit_Framework_TestCase
{
    public function testCompileShouldReturnTypeString()
    {
        $compiler = new Compiler(new RootNode());
        $this->assertInternalType('string', $compiler->compile());
    }

    public function testCompileShouldReturnEmptyString()
    {
        $compiler = new Compiler(new RootNode());
        $this->assertEquals('', $compiler->compile());
    }
}
