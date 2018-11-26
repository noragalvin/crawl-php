<?php
    require_once("../system/library/curl.php");
    $curl = new cURL();
    $url = "https://readmanhua.net/filterList?";
    foreach ($_GET as $key => $value) {
        $url .= $key . "=" . $value . "&";
    }
    $url = substr($url, 0, strlen($url)-1);
    print_r("filter");
    die();
    $data = $curl->get($url);
    echo $data;
    // print_r($url);
    // die();
?>