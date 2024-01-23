<?php

if (!function_exists('array_find')) {
    function array_find($fill, $value, $array)
    {
        foreach ($array as $key => $val) {
            if ($val[$fill] === $value) {
                return $val;
            }
        }
        return [];
    }
}