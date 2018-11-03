<?php
    require_once("../system/library/curl.php");
    $curl = new cURL();
    $url = "https://readmanhua.net/search?";
    foreach ($_GET as $key => $value) {
        $url .= $key . "=" . $value . "&";
    }
    $url = substr($url, 0, strlen($url)-1);
    $data = $curl->get($url);
    // echo $url;   
    echo $data;
?>