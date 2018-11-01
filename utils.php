<?php
function xsearhs($pattern,$string){
    $result = preg_match_all($pattern,$string);
    echo '<pre>';
    var_dump($result);
    echo '</pre>';
//    return $result[0];
}