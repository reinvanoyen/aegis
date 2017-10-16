<?php

namespace Aegis;

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
    public function add($nodeClassName);

    /**
     * @param ParserInterface $parser
     * @return mixed
     */
    public function parse(ParserInterface $parser);
}
