<?php
/**
 * A hook with more parameter tags than actual parameters.
 *
 * @param string $first  The first parameter.
 * @param string $second The second parameter.
 * @param string $third  A stale parameter that no longer exists.
 */
$result = apply_filters( 'extra_params_hook', $first, $second );
