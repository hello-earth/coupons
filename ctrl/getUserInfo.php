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
    if(""!=$uid){
        require_once './db_inc.php';
        $sql="select nickname,total_usetimes,today_usetimes,remaining from spd_wxprp_user WHERE uid='$uid' ";
        $db = (new DaoHelper())->getDBInstant();
        $result=$db->query($sql);
        if(mysqli_num_rows($result)>0) {
            $row = $result->fetch_row();
            $resultstr=array("status"=>0, "uid"=>$uid, "name"=>$row[0], "tottaltime"=>$row[1],"todayused"=>$row[2],"stillhave"=>$row[3]);
            die(json_encode($resultstr));
        }
    }
}

