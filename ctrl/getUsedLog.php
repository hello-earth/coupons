<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/8
 * Time: 16:24
 */

header("Content-Type: json;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

if(isset($_GET["uid"]) ) {
    $uid = $_GET['uid'];
    if(""!=$uid){
        require_once './db_inc.php';
        $sql="select provider from spd_wxprp_user WHERE uid='$uid'";
        $db = (new DaoHelper())->getDBInstant();
        $result=$db->query($sql);
        if(mysqli_num_rows($result)>0) {
            $row = $result->fetch_row();
            $provider = $row[0];
            $sql="select id,identification,createtime,spdurl from wxprp_log WHERE user='$provider' AND DATEDIFF(createtime,NOW())=0 ORDER BY id DESC LIMIT 5";
            $result=$db->query($sql);
            if(mysqli_num_rows($result)>0) {
                $resultstr=array();
                while ($row = mysqli_fetch_row($result)) {
                    $pid = $row[1];
                    $time = explode(" ",$row[2])[1];
                    $spdurl =$row[3];
                    array_push($resultstr,array("pid"=>"$pid","content"=>"<a href='$spdurl'>".$pid."号红包</a>", "timestamp"=>"$time"));
                }
                die(json_encode($resultstr));
            }
        }
    }
}