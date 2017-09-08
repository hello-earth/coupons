<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/8
 * Time: 16:24
 */

header("Content-Type: text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

if(isset($_GET["uid"]) ) {
    $uid = $_GET['uid'];
    $resultstr=array(array("pid"=>"1001","content"=>"1001号红包", "timestamp"=>"19:39:10"),array("pid"=>"1002","content"=>"1002号红包", "timestamp"=>"19:39:20"),
        array("pid"=>"1003","content"=>"1003号红包", "timestamp"=>"19:39:30"),array("pid"=>"1004","content"=>"1004号红包", "timestamp"=>"19:39:40"),
        array("pid"=>"1005","content"=>"1005号红包", "timestamp"=>"19:39:50"),);
    die(json_encode($resultstr));
}
