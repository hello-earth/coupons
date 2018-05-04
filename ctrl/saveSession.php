<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/4
 * Time: 10:21
 */


header("Content-Type: json;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

$resultstr=array("r"=>0,"msg"=>"数据已保存，任务刷新需要一定时间，2分钟左右看下是否正常。");
$dataMap = [
    "校长" =>"oggN1jqzN2_0rFf-WasP2BPNwmu0",
    "hs" => "oggN1jjt_EK_4W6yVaI1Fz8v6Q4I",
    "步登" => "oggN1jjgH1Q49Pl9ZHUpBG8wnuHc",
    "果子" => "oggN1jsH-MBFGPeYkXLiZYNv1xFA",
    "Young" => "oggN1ju2F9rG7wEnDbksCGOLLF18",
    "mega" => "oggN1jvC171jB3o6nUxboa1cUHJI",
    "56" => "oggN1jiAIzgiLk3tkzaA80kx9bZo",
    "c9" => "oggN1jnkahWBFOMAkx1Bf-shg1s4"
];


function getLocalSession($method){
    $path = "webdictionary.txt";
    if($method==1)
        $path = "bowling.txt";
    $myfile = @fopen($path, "r");
    if($myfile) {
        $content = fread($myfile, filesize("webdictionary.txt"));
        fclose($myfile);
        return $content;
    }else{
        return "{'version':0000000000000,'data':[]}";
    }
}

function saveLocalSession($method, $txt){
    $path = "webdictionary.txt";
    if($method==1)
        $path = "bowling.txt";
    $myfile = @fopen($path, "w");
    if($myfile) {
        fwrite($myfile, $txt);
        fclose($myfile);
        return 1;
    }else{
        return 0;
    }
}

function findObj($arr,$obj){
    $num = count($arr);
    for($i = 0; $i < $num; $i++){
        if($arr[$i]->name==$obj){
            return $i;
        }
    }
    return -1;
}

if(isset($_POST["pw"]) && isset($_POST["method"]) && isset($_POST["sess"]) ) {
    $pw = $_POST['pw'];
    $sess = $_POST['sess'];
    $method = $_POST["method"];

    $content = getLocalSession($method);
    $reStr = json_decode($content);
    if(@$reStr->data==null)
        @$reStr->data = [];

    $index = findObj($reStr->data,$pw);
    if($index>=0){
        $reStr->version = time();
        $arr = ($reStr->data);
        $arr[$index]->sess = $sess;
    }else{
        if(isset($dataMap[$pw])){
            $reStr->version = time();
            array_push($reStr->data, array('name'=>$pw,'sess'=>$sess,"uid"=>$dataMap[$pw],"date"=>date("d"),"runtime"=>0));
        }else{
            $resultstr['msg'] = '非受邀用户无法使用';
            die(json_encode($resultstr));
        }
    }
    if(0==saveLocalSession($method, json_encode($reStr))){
        $resultstr['msg'] = '数据保存失败';
    }
}else{
    $resultstr['r'] = '1';
    $resultstr['msg'] = '非法参数';
}

die(json_encode($resultstr));
