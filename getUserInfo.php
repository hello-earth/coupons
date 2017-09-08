<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/8
 * Time: 15:42
 */

header("Content-Type: text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

if(isset($_GET["uid"]) ) {
    $uid = $_GET['uid'];
    $resultstr=array("status"=>0, "uid"=>$uid, "name"=>"Young.LIU", "tottaltime"=>"1025","todayused"=>"10","stillhave"=>"100");
    die(json_encode($resultstr));
}

