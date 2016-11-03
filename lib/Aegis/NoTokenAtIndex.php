<?php

namespace Aegis;

class NoTokenAtIndex extends \Exception
{
    public function __construct($index)
    {
        parent::__construct('No token found in the TokenStream at index '.$index);
    }
}
