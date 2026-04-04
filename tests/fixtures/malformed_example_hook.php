<?php
/**
 * A hook with a malformed example.
 *
 * Example:
 * add_filter( 'malformed_example_hook', function( $value ) {
 *     return $value;
 * } );
 *
 * @param string $value The value.
 */
$result = apply_filters( 'malformed_example_hook', $value );
