<?php


header("Content-Type: json;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
$resultstr=array("r"=>0,"url"=>"","msg"=>"");

function isNotEmpty($request,$url=""){
    if($url=="") $url = prp_url;
    $result = $request->get($url);
    $lable = "images/but_open.png";
    $pos = strpos($result, $lable);
    if(($pos !== false)){
        $pos = strpos($result, "\"&dataDt=\"+'");
        return substr($result,$pos+strlen("\"&dataDt=\"+'"),8);
    }
    return "";
}

function checkSPDAvailable($request){
    try{
        $url="https://weixin.spdbccc.com.cn/wxrp-page-redpacketsharepage/share?packetId=1D3U2X7F7C6FJQDJ450035770-150460727900086183b82&noCheck=1&hash=4&dataDt=20170905";
        $result = $request->get($url);
        return strpos($result,"images/share_out.jpg") != false;
    }catch (Exception $ex){
        return false;
    }
}

function convertUrlQuery($query) {
    $queryParts = explode('&', $query);
    $params = array();
    foreach ($queryParts as $param) {
        $item = explode('=', $param);
        $params[$item[0]] = $item[1];
    }
    return $params;
}

function runDB($db,$sql,$remaining){
    if(!$db->query($sql)){
        $db->rollback();
        $db->autocommit(true);
        $sqll = "update spd_wxprp_user set remaining=".($remaining+5)." WHERE provider='".$wxid."'";
        $db->query($sqll);
        $resultstr["status"] = 1;
        $resultstr["msg"] = "数据库操作发生错误，异常退出，请稍后再试。SQL=".$sql;
        die(json_encode($resultstr));
    }
}

function runDBB($db,$sql){
    if(!$db->query($sql)){
        $db->rollback();
        $db->autocommit(true);
        $resultstr["status"] = 1;
        $resultstr["msg"] = "数据库操作发生错误，异常退出，请稍后再试。";
        die(json_encode($resultstr));
    }
}


if(isset($_GET["uid"]) ) {
    $uid = $_GET['uid'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if ("" != $uid && strpos($user_agent, 'MicroMessenger')>0) {
        include_once "./NetUtil.php";
        $request = new Request();

        require_once './db_inc.php';
        $db = (new DaoHelper())->getDBInstant();
        $db->autocommit(false);
        $sql = "select remaining,today_usetimes, u1,u2,u3,u4,u5,u6,u7,u8,u9,u10,u11,u12,u13,u14,u15,provider from spd_wxprp_user WHERE uid='" . $uid . "' FOR UPDATE";
        $result = $db->query($sql);
        if (mysqli_num_rows($result) > 0) {
            $row = $result->fetch_row();
            if ($row == null) {
                $resultstr["r"] = 1;
                $resultstr["msg"] = "数据错误，请稍后再试";
            } else {
                $remaining = $row[0];
                $today_usetimes = $row[1];
                $wx = $row[17];
                if ($remaining < 0) {
                    $resultstr["r"] = 1;
                    $resultstr["msg"] = "账号涉及骗包已被加黑处理(同个红包既分享给机器人又分享给别人)，此次分享不记录。";
                } elseif ($remaining == 0) {
                    $resultstr["r"] = 1;
                    $resultstr["msg"] = "你所有应返还的红包都已领取。\n如有未返回链接，请回复【历史红包】取回。";
                } elseif ($today_usetimes >= 15) { //超过15个了
                    $resultstr["r"] = 1;
                    $resultstr["msg"] = "你今天通过分享得到的红包已超过上限【15个】，今天将不能再开红包，请明天再来！";
                } else {
                    $now = date('Y-m-d H:i:s',time());
                    $spdurl="";
                    $sql = "select id, url,usetimes,u1,u2,u3,u4,u5,provider from spd_wxprp WHERE usetimes<5 AND provider NOT IN ('" . $wx . "','" . $row[2] . "','" . $row[3] . "','" . $row[4] . "','" . $row[5] .
                        "','" . $row[6] . "','" . $row[7] . "','" . $row[8] . "','" . $row[9] . "','" . $row[10] . "','" . $row[11] . "','" . $row[12] . "','" . $row[13] . "','" . $row[14] . "','" . $row[15] . "','" . $row[16] . "')  AND u1<>'" . $wx .
                        "' AND u2<>'" . $wx . "' AND u3<>'" . $wx . "' AND u4<>'" . $wx . "' AND u5<>'" . $wx . "' AND  (NOW()- `createtime` <86400) group by provider ORDER BY id limit 1 FOR UPDATE";
                    $result = $db->query($sql);
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_row($result);
                        $url = $row[1];
                        $sql = "update  spd_wxprp set usetimes=" . ($row[2] + 1) . ", usetime='" . $now . "',u" . ($row[2] + 1) . "='" . $wx . "' WHERE url='" . $url . "' AND id=" . $row[0];
                        runDBB($db,$sql);
                        $sql = "insert into wxprp_log(id,spdurl,user,identification,createtime) values(NULL,'" . $url . "','" . $wx . "'," . $row[0] . ",'".$now."')";
                        runDBB($db,$sql);
                        $uus = "u" . ($today_usetimes + 1) . "='" . $row[8] . "',";

                        $arr = parse_url($url);
                        $arr_query = convertUrlQuery($arr['query']);
                        $spdurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe9d7e3d98ec68189&redirect_uri=https://weixin.spdbccc.com.cn/wxrp-page-redpacketsharepage/judgeOpenId?noCheck%3D1%26param1%3D".$arr_query["packetId"]."%26hash%3D".$arr_query["hash"]."%26dataDt%3D".$arr_query["dataDt"]."&response_type=code&scope=snsapi_base&state=STATE";

                        $remain = $remaining - 1;
                        $uus .= ("today_usetimes=".($today_usetimes + 1));
                        $sql = "update spd_wxprp_user set ".$uus.", remaining=".$remain.", lasttime='".$now."' WHERE provider='".$wx."'";
                        runDBB($db,$sql);
                        $resultstr["r"] = 0;
                        $resultstr["url"] = $spdurl;
                        $db->commit();
                        $db->autocommit(true);
                    }else{
                        $resultstr["r"] = 1;
                        $resultstr["msg"] = "红包池不足，请稍后再试。";
                    }
                }
            }
        } else {
            $resultstr["r"] = 1;
            $resultstr["msg"] = "未找到账号信息";
        }
    }else{
        $resultstr["r"] = 1;
        $resultstr["msg"] = "参数错误";
    }
}else{
    $resultstr["r"] = 1;
    $resultstr["msg"] = "参数错误";
}
die(json_encode($resultstr));
