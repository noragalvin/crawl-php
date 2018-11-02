<?php
    require_once("../system/start.php");
    $url = "https://readmanhua.net/filterList?";
    foreach ($_GET as $key => $value) {
        $url .= $key . "=" . $value . "&";
    }
    $url = substr($url, 0, strlen($url)-1);
    $data = $curl->get($url);
    echo $data;
    // print_r($url);
    // die();
?>