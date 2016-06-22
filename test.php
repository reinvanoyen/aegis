<?php

$code = "'string' . 'something'";

eval( '$variable=' . $code .';' );

echo $variable;