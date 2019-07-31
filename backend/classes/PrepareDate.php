<?php

class PrepareDate {

    function __construct() {

    }

    public function prepare($str) {
        if(is_null($str)) {
            return null;
        }
        return date('d-m-Y', strtotime($str));
    }
}