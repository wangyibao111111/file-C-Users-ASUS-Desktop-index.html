
<?php


$localurl = "API��ַ";

$username = "�ֻ���";

$password = "����";



//��������

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



//��ȡ���Ƹ赥

function get_rec_res($cookies){

global $localurl;

$url = $localurl."/recommend/resource";

return json_decode(getcurl($url,$cookies,0),true);

}



//��ȡ�赥�и���

function get_song($cookies,$id){

global $localurl;

$url = $localurl."/playlist/detail?id={$id}";

return json_decode(getcurl($url,$cookies,0),true);

}



//�򿨸���

function daka($cookies,$id){

global $localurl;

$url = $localurl."/scrobble?id={$id}&time=71&timestamp=".rand(1, 100000);

getcurl($url,$cookies,0);

}



//ǩ��

function qiandao($cookies){

global $localurl;

$urland = $localurl."/daily_signin";

$urlpc = $localurl."/daily_signin?type=1";

getcurl($urland,$cookies,0);

getcurl($urlpc,$cookies,0);

}



//��¼

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

echo "ִ�� {$j} ��\n";

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
