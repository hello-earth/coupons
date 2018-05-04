<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/4
 * Time: 10:21
 */


header("Content-Type: json;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

$resultstr=array("r"=>0,"msg"=>"数据已保存");
$dataMap = [
    "校长" =>"oggN1jqzN2_0rFf-WasP2BPNwmu0",
    "hs" => "oggN1jjt_EK_4W6yVaI1Fz8v6Q4I",
    "步登" => "oggN1jjgH1Q49Pl9ZHUpBG8wnuHc",
    "果子" => "oggN1jsH-MBFGPeYkXLiZYNv1xFA"
];


function getLocalSession(){
    $myfile = @fopen("webdictionary.txt", "r");
    if($myfile) {
        $content = fread($myfile, filesize("webdictionary.txt"));
        fclose($myfile);
        return $content;
    }else{
        return "{'version':0000000000000,'data':[]}";
    }
}

function saveLocalSession($txt){
    $myfile = @fopen("webdictionary.txt", "w");
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

if(isset($_POST["pw"]) && isset($_POST["sess"]) ) {
    $pw = $_POST['pw'];
    $sess = $_POST['sess'];
    $content = getLocalSession();
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
    if(0==saveLocalSession(json_encode($reStr))){
        $resultstr['msg'] = '数据保存失败';
    }
}else{
    $resultstr['r'] = '1';
    $resultstr['msg'] = '非法参数';
}

die(json_encode($resultstr));
