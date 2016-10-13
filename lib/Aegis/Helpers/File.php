<?php

namespace Aegis\Helpers\File;

/*boolean*/ function scopedRequire( $filename, $vars = [] )
{
	extract( $vars );
	require $filename;
}
