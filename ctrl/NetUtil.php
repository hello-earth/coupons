<?php

/**
 * Created by PhpStorm.
 * User: Young
 * Date: 2017/9/10
 * Time: 23:48
 */
class Request{

    public function get($url){
        $headers = array('User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 9_3_5 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Mobile/13G36 MicroMessenger/6.3.31 NetType/WIFI Language/zh_CN',
            'ContentType: application/json; charset=UTF-8');
        $result="";
        try{
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  //SSL 报错时使用
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  //SSL 报错时使用
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);
            curl_close($ch);
        }catch (Exception $ex){
            print_r($ex);
        }
        return $result;
    }

}