<?php

class Misc {

    private static $f3;

    public function __construct() {
       self::$f3 = Base::instance();

        // anonymous functions for templates
        self::$f3->set('to_currency', [$this, 'to_currency']);
        self::$f3->set('split_datetime', [$this, 'split_datetime']);
        self::$f3->set('is_active_nav', [$this, 'is_active_nav']);
        self::$f3->set('space_to_newline', [$this, 'space_to_newline']);
        self::$f3->set('encode', [$this, 'encode']);
    }

    // adds trailing 0s to currency (i.e. 1.5 -> 1.50)
    static function to_currency($num) {
        return number_format((float)$num, 2, '.', '');
    }

    // split datetime into seperate date & time array
    static function split_datetime($datetime) {
        $dt = new DateTime($datetime);
        $date = $dt->format('d/m/Y');
        $time = $dt->format('H:i:s');
        return array($date, $time);
    }

    // provide active class if nav corresponds to current page
    static function is_active_nav($nav_name) {
        if (self::$f3->get('breadcrumbs')[0]['name'] == $nav_name) {
            return true;
        }
        return false;
    }

    // check all required fields were posted
    static function check_required_fields($required_fields) {
        foreach ($required_fields[0] as $i => $field) {
            if (self::$f3->get('POST.' . $field) == '') {
                self::$f3->set('error', 'Please fill in the \'' . $required_fields[1][$i] . '\' field.');
                return false;
            }
        }
        return true;
    }

    // filter out unwanted post fields
    static function copyFrom_filter($target, $inclusive_fields) {
        $target->copyfrom('POST', function($val) use (&$inclusive_fields) {
            // the 'POST' array is passed to our callback function
            return array_intersect_key($val, array_flip($inclusive_fields));
        });
    }

    // validate hex (6 and 3 char variations)
    static function validate_hex($hex) {
        $hex_trimmed = ltrim($hex, '#');
        return (ctype_xdigit($hex_trimmed) && (strlen($hex_trimmed) == 3 || strlen($hex_trimmed) == 6));
    }

    // get first URL param
    static function get_param($param_position=0) {
        return explode('/', self::$f3->get('PARAMS.0'))[$param_position+1];
    }

    // convert space to new line
    static function space_to_newline($str) {
        return str_replace(" ","<br>", $str);
    }

    // encode quotes
    static function encode($str) {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    // var_dump debug
    static function dump($var) {
        var_dump($var);
        exit;
    }

}
