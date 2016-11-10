<?php

namespace Aegis;

interface NodeCollectionInterface
{
	public function add($nodeClassName);
	public function parse(ParserInterface $parser);
}
