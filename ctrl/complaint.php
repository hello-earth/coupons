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
    if($url=="") $url = $this->prp_url;
    try {
        $request = new Request();
        $result = $request->get($url);
        $pos = strpos($result, "images/but_open.png");
        if (($pos !== false)) {
            $pos = strpos($result, "\"&dataDt=\"+'");
            return substr($result, $pos + strlen("\"&dataDt=\"+'"), 8);
        }
        return "";
    }catch (Exception $ex){
        return "-1";
    }
}

function checkEmpty($db,$uid,$pid,$resultstr){
    $sql="select provider from spd_wxprp_user WHERE uid='$uid'";
    $result=$db->query($sql);
    if(mysqli_num_rows($result)>0) {
        $row = $result->fetch_row();
        $wxid = $row[0];
        $sql = "select spd_wxprp.url,spd_wxprp_log.usetime,spd_wxprp_log.provider,spd_wxprp_log.createtime from spd_wxprp_log,spd_wxprp WHERE spd_wxprp_log.pid=$this->pid AND FIND_IN_SET('".$this->wxid."',spd_wxprp_log.user) limit 1";
        $result=$this->db->query($sql);
        if(mysqli_num_rows($result)>0) {
            $row = $result->fetch_row();
            $url = $row[0];
            $usetimes = $row[1];
            $datestr = isNotEmpty($url);
            if ($datestr!="-1" && $datestr!="") {
                $resultstr["msg"] ='该分享不是空包，请确认后再试。恶意举报会被拉黑处理！';
            } else{
                recUserRemain($db,$wxid,$usetimes,$pid,$resultstr,$datestr);
            }
        }else{
            $resultstr["msg"] = '你没有获得该红包分享。<br>恶意举报会被拉黑处理！';
        }
    }else{
        $resultstr["msg"] ='未找到账号信息。';
    }
    die(json_encode($resultstr));
}

function checkOverduceByPID($db,$uid,$pid,$resultstr){
    $sql = "select url,usetimes,provider,u1,u2,u3,u4,u5,createtime from spd_wxprp WHERE id=$pid";
    $result=$db->query($sql);
    if(mysqli_num_rows($result)>0) {
        $row = $result->fetch_row();
        $url = $row[0];
        $createtime = $row[8];
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
                if ($datestr=="-1" || ($datestr!="" && floor(time()-strtotime($createtime))>86400)) {
                    recUserRemain($db,$wxid,0,$pid,$resultstr,$datestr);
                } else{
                    $resultstr["msg"] = '该分享没有过期，请确认后再试。恶意举报会被拉黑处理！';
                }
            }
        }else{
            $resultstr["msg"] = '未找到账号信息。';
        }
    }else{
        $resultstr["msg"] ='该红包不存在，请确认后再试。';
    }

    $sql="select provider from spd_wxprp_user WHERE uid='$uid'";
    $result=$db->query($sql);
    if(mysqli_num_rows($result)>0) {
        $row = $result->fetch_row();
        $wxid = $row[0];
        $sql = "select spd_wxprp.url,spd_wxprp_log.usetime,spd_wxprp_log.provider,spd_wxprp_log.createtime from spd_wxprp_log,spd_wxprp WHERE spd_wxprp_log.pid=$this->pid AND FIND_IN_SET('".$this->wxid."',spd_wxprp_log.user) limit 1";
        $result=$this->db->query($sql);
        if(mysqli_num_rows($result)>0) {
            recUserRemain($db,$wxid,0,$pid,$resultstr,"20180325");
        }else{
            $resultstr["msg"] = '你没有获得该红包分享。<br>恶意举报会被拉黑处理！';
        }
    }else{
        $resultstr["msg"] ='未找到账号信息。';
    }
    die(json_encode($resultstr));
}

function recUserRemain($db,$wxid,$usetimes,$pid,$resultstr,$datestr){
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
                    if($datestr!="-1") {
                        $sql = "select spd_wxprp_log.user from spd_wxprp_log WHERE pid=$pid";
                        $result=$this->db->query($sql);
                        if(mysqli_num_rows($result)>0) {
                            $row = $result->fetch_row();
                            $user = $row[0];
                            $user = str_replace($this->wxid.",","",$user);
                            if ($usetimes == 0) {
                                //过期
                                $sql = "update spd_wxprp_log set usetime=97,user='".$user."' WHERE pid=$pid";
                                $this->db->query($sql);
                            } else {
                                //空包
                                $sql = "update spd_wxprp_log set usetime=98 ,user='".$user."' WHERE pid=$pid";
                                $this->db->query($sql);
                            }
                        }else{
                            $this->resultstr["msg"] = "系统错误，请稍后再试。";
                        }
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


