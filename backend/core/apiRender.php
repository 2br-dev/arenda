<?php

function apiRender($result, $key = null) {
    $array = []; 
    if(!is_null($key)) {
        $array['code'] = $key;
        $array['result'] = $result;
    } else {
        $array = $result;
    }
    //
    exit(json_encode($array));
}