<?php

namespace Aegis\Contracts;

interface EngineInterface
{
    public function evaluate(string $input): string;
    public function getCompiler(): CompilerInterface;
}
