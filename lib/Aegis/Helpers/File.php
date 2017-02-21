<?php

namespace Aegis\Helpers\File;

function scopedRequire($filename, $vars = [])
{
    extract($vars);
    require $filename;
}