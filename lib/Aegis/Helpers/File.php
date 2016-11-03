<?php

namespace Aegis\Helpers\File;

function scopedRequire($filename, $vars = [])
{
    extract($vars);
    require $filename;
}

function write($filename, $content)
{
    $dir = dirname($filename);

    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }

    file_put_contents($filename, $content);
}
