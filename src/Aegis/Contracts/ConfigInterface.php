<?php

namespace Aegis\Contracts;

interface ConfigInterface
{
    public function get($key);
    public function has($key): bool;
}
