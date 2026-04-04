<?php
/**
 * A hook with fewer parameter tags than actual parameters.
 *
 * @param string $first The first parameter.
 */
$result = apply_filters( 'missing_params_hook', $first, $second, $third );
