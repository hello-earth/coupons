<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/8
 * Time: 13:33
 */

$url = $_GET["url"];
require_once "jssdk.php";
$jssdk = new JSSDK("wx0f8ccc1d47e0d1a6", "8a7916a7742e4b995bbacad104e8e60a");
$signPackage = $jssdk->GetSignPackage($url);
die(json_encode($signPackage));

?>