<?php

use Aegis\Compiler;
use Aegis\Node\RootNode;

class CompilerTest extends PHPUnit_Framework_TestCase
{
    private $compiler;

    public function setup()
    {
        $this->compiler = new Compiler();
    }

    public function testCompileShouldReturnTypeString()
    {
        $this->assertInternalType('string', $this->compiler->compile(new RootNode()));
    }

    public function testCompileShouldReturnEmptyString()
    {
        $this->assertEquals('', $this->compiler->compile(new RootNode()));
    }
}
