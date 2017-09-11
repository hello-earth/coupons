<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/11
 * Time: 10:17
 */

header("Content-Type: json;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
include_once "./NetUtil.php";

function isNotEmpty($url=""){
    $request = new Request();
    $result = $request->get($url);
    $lable = "images/but_open.png";
    $pos = strpos($result, $lable);
    if(($pos !== false)){
        $pos = strpos($result, "\"&dataDt=\"+'");
        return substr($result,$pos+strlen("\"&dataDt=\"+'"),8);
    }
    return "";
}

function checkEmpty($db,$uid,$pid,$resultstr){
    $sql = "select url,usetimes,provider,u1,u2,u3,u4,u5 from spd_wxprp WHERE id=$pid";
    $result=$db->query($sql);
    if(mysqli_num_rows($result)>0) {
        $row=$result->fetch_row();
        $url = $row[0];
        $usetimes = $row[1];
        $users = Array($row[3],$row[4],$row[5],$row[6],$row[7]);
        $sql="select provider from spd_wxprp_user WHERE uid='$uid'";
        $result=$db->query($sql);
        if(mysqli_num_rows($result)>0) {
            $row = $result->fetch_row();
            $wxid = $row[0];
            if (!in_array($wxid, $users)){
                $resultstr["msg"] = '你没有获得该红包分享。<br>恶意举报会被拉黑处理！';
            }else{
                $datestr = isNotEmpty($url);
                if ($datestr!="") {
                    $resultstr["msg"] ='该分享不是空包，请确认后再试。<br>恶意举报会被拉黑处理！';
                } else{
                    recUserRemain($db,$wxid,$usetimes,$pid,$resultstr);
                }
            }
        }else{
            $resultstr["msg"] ='未找到账号信息。';
        }
    } else{
        $resultstr["msg"] ='该红包不存在，请确认后再试。';
    }
    die(json_encode($resultstr));
}

function checkOverduceByPID($db,$uid,$pid,$resultstr){
    $sql = "select url,usetimes,provider,u1,u2,u3,u4,u5,createtime from spd_wxprp WHERE id=$pid";
    $result=$db->query($sql);
    if(mysqli_num_rows($result)>0) {
        $row = $result->fetch_row();
        $url = $row[0];
        $users = Array($row[3], $row[4], $row[5], $row[6], $row[7]);
        $sql = "select provider from spd_wxprp_user WHERE uid='$uid'";
        $result = $db->query($sql);
        if (mysqli_num_rows($result) > 0) {
            $row = $result->fetch_row();
            $wxid = $row[0];
            if (!in_array($wxid, $users)) {
                $resultstr["msg"] = '你没有获得该红包分享。<br>恶意举报会被拉黑处理！';
            } else {
                $datestr = isNotEmpty($url);
                if ($datestr != "" && floor(time() - strtotime($row[8])) > 86400) {
                    recUserRemain($db,$wxid,0,$pid,$resultstr);
                } else {
                    $resultstr["msg"] = '该分享没有过期，请确认后再试。恶意举报会被拉黑处理！';
                }
            }
        }else{
            $resultstr["msg"] = '未找到账号信息。';
        }
    }else{
        $resultstr["msg"] ='该红包不存在，请确认后再试。';
    }
    die(json_encode($resultstr));
}

function recUserRemain($db,$wxid,$usetimes,$pid,$resultstr){
    try{
        $sql = "select remaining,today_usetimes,nickname from spd_wxprp_user WHERE provider='$wxid' limit 1";
        $result=$db->query($sql);
        if(mysqli_num_rows($result)>0) {
            $row = $result->fetch_row();
            $remain = $row[0];
            if ($remain >= 0) {
                $today_usetimes = $row[1];
                if ($today_usetimes <= 0){
                    $remain = -10;
                }
                $sql = "update spd_wxprp_user set remaining=($remain + 1),today_usetimes=($today_usetimes - 1) WHERE provider='$wxid'";
                if ($db->query($sql)) {
                    if ($usetimes == 0) {
                        $sql = "update  spd_wxprp set usetimes=7 WHERE id=$pid";
                        $db->query($sql);
                    } elseif ($usetimes < 5) {
                        $sql = "update  spd_wxprp set usetimes=6 WHERE id=$pid";
                        $db->query($sql);
                    }
                    if($remain>=0){
                        $sql = "delete from wxprp_log WHERE identification=$pid";
                        if($db->query($sql)){
                            $resultstr["status"] = 0;
                            $resultstr["msg"] = "举报已收到!<br>系统退回一个分享，可立即使用。";
                        } else{
                            $resultstr["msg"] = "系统错误，请稍后再试。";
                        }
                    }
                    else
                        $resultstr["msg"] = "恶意举报，你已被拉黑。";
                } else
                    $resultstr["msg"] = "系统错误，请稍后再试。";
            }else
                $resultstr["msg"] = "被拉黑账户不允许举报。";
        }else
            $resultstr["msg"] = '系统错误，未找到你的身份信息。';
    }catch (Exception $ex){
        $resultstr["msg"] = $ex;
    }
    die(json_encode($resultstr));
}

if(isset($_GET["uid"]) && isset($_GET["pid"]) && isset($_GET["which"])) {
    $uid = $_GET['uid'];
    $pid = $_GET['pid'];
    $which = $_GET['which'];

    $resultstr=array("status"=>1, "msg"=>"","pid"=>$pid,"which"=>$which);
    if(""!=$uid && $pid!="" && $which!=""){
        require_once './db_inc.php';
        $db = (new DaoHelper())->getDBInstant();
        try{
            if($which==0){
                checkEmpty($db,$uid,$pid,$resultstr);
            }else{
                checkOverduceByPID($db,$uid,$pid,$resultstr);
            }
        }catch (Exception $ex){
            $resultstr["msg"] = $ex;
        }
    }
    die(json_encode($resultstr));
}


