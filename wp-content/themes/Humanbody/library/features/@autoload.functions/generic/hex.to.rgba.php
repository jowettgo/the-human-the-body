<?php

/**
 * [hex_rgba convert hex color to rgba]
 * @param  string $hex   hex color
 * @param  float $alpha  alpha, from 0 to 1
 * @return string        css rgba color
 */
function hex_rgba($hex, $alpha) {
	list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
	return "rgba($r, $g, $b, $alpha)";
}
