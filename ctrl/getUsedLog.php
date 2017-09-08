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
    if(""!=$uid){
        require_once './db_inc.php';
        $sql="select provider from spd_wxprp_user WHERE uid='$uid'";
        $db = (new DaoHelper())->getDBInstant();
        $result=$db->query($sql);
        if(mysqli_num_rows($result)>0) {
            $row = $result->fetch_row();
            $provider = $row[0];
            $sql="select id,usetime,identification from wxprp_jump WHERE user='$provider' AND DATEDIFF(usetime,NOW())=0 ORDER BY id DESC LIMIT 5";
            $result=$db->query($sql);
            if(mysqli_num_rows($result)>0) {
                $resultstr=array();
                while ($row = mysqli_fetch_row($result)) {
                    $pid = $row[0];
                    $time = explode(" ",$row[1])[1];
                    $identification =$row[2];
                    $sql="select url from spd_wxprp WHERE id=".$identification;
                    $result1=$db->query($sql);
                    if(mysqli_num_rows($result1)>0) {
                        $row = mysqli_fetch_row($result1);
                        array_push($resultstr,array("pid"=>"$pid","content"=>"<a href='$row[0]'>".$pid."号红包</a>", "timestamp"=>"$time"));
                    }
                }
                die(json_encode($resultstr));
            }
        }
    }
}
