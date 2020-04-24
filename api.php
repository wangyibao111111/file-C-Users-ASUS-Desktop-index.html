
<?php


$localurl = "API地址";

$username = "手机号";

$password = "密码";



//访问链接

function getcurl($url,$cookies,$headid){

$ch = curl_init();

curl_setopt($ch, CURLOPT_COOKIE, $cookies);

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_HEADER, $headid);

$output = curl_exec($ch);

curl_close($ch);

return $output;

}



//获取日推歌单

function get_rec_res($cookies){

global $localurl;

$url = $localurl."/recommend/resource";

return json_decode(getcurl($url,$cookies,0),true);

}



//获取歌单中歌曲

function get_song($cookies,$id){

global $localurl;

$url = $localurl."/playlist/detail?id={$id}";

return json_decode(getcurl($url,$cookies,0),true);

}



//打卡歌曲

function daka($cookies,$id){

global $localurl;

$url = $localurl."/scrobble?id={$id}&time=71&timestamp=".rand(1, 100000);

getcurl($url,$cookies,0);

}



//签到

function qiandao($cookies){

global $localurl;

$urland = $localurl."/daily_signin";

$urlpc = $localurl."/daily_signin?type=1";

getcurl($urland,$cookies,0);

getcurl($urlpc,$cookies,0);

}



//登录

function login($username,$password){

global $localurl;

$url = $localurl."/login/cellphone?phone={$username}&password={$password}";

$data = getcurl($url,0,1);

if(preg_match_all('/Set-Cookie:(.*);/iU',$data,$str)==0)

die($data);

$cookies = $str[1][0].";".$str[1][1].";".$str[1][2].";";

return $cookies;

}



function run($username,$password){

global $localurl;

$cookies = login($username,$password);

qiandao($cookies);

$songslist = get_rec_res($cookies);

for($k=0;$k<(count($songslist["recommend"]));$k++){

$songlist = get_song($cookies,$songslist["recommend"][$k]["id"]);

for($j=0;$j<(count($songlist["privileges"]));$j++){

daka($cookies,$songlist["privileges"][$j]["id"]);

if(($j/10)==0){

sleep(1);

}

if(($j==(count($songlist["privileges"])-1))||$j==300){

echo "执行 {$j} 首\n";

sleep(10);

break 1;

}

}

}

}



function main_handler($event, $context) {

global $username;

global $password;

run($username,$password);

return "OK";

}


?>
