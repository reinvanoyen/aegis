<?php

namespace Aegis\Contracts;

/**
 * Interface NodeCollectionInterface
 * @package Aegis
 */
interface NodeCollectionInterface
{
    /**
     * @param $nodeClassName
     * @return mixed
     */
    public function register($nodeClassName);

    /**
     * @return array
     */
    public function getAll(): array;
}
