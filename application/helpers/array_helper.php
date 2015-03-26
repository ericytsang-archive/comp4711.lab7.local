<?php

/**
 * turns an array of strings into a comma separated list
 *
 * @param  string[] $string_array array of strings to turn into comma separated
 *   list
 * @param  string   $null_string returned if $string_array has 0 elements
 *
 * @return [file_header] [class_header] [type] [description]
 */
function to_comma_list($string_array,$null_string = 'none')
{
    $comma_list = '';
    $first = true;
    foreach($string_array as $string)
    {
        if($string !== null)
        {
            $comma_list .= ($first) ? '' : ', ';
            $comma_list .= $string;
            $first = false;
        }
    }
    return (count($string_array) == 0) ? $null_string : $comma_list;
}
