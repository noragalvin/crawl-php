<?php
require_once('system/start.php');

$URL = "https://readmanhua.net/";

$id = $_GET['id'];
$chap = $_GET['chap'];

$result = $curl->get($URL . "manga/" . $id . "/" . $chap);

echo $result;
die();