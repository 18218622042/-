<?php
require __DIR__ . "/qcloudsms_php/src/index.php";
// use qcloudsms_php\src\SmsMultiSender;
use Qcloud\Sms\SmsSingleSender;
$appid=1400199656;
$appkey="f9f8f92884dce1c99a4928cd07c17690";

$smsSign="灵犀出行";
$openid=$_GET['openid'];
$mode=$_GET['mode'];
if($mode==1){$templateId=312627;}
else{$templateId=329372;}
$ip ="localhost";
$dbuser="root";  //数据库初始用户名和密码
$psw="lxcs123";  
$database="lx_information";

$con=mysqli_connect($ip,$dbuser,$psw,$database);
if (mysqli_connect_errno($con)) 
{ 
    echo "连接 MySQL 失败: " . mysqli_connect_error(); 
}  

$phoneNumbers=array();


$sql="select contacts_phone_1 from contacts1 where openID ='$openid'";
$result = mysqli_query($con, $sql);
if($result==null)
{
	echo "返回空";
}
$results = mysqli_fetch_assoc($result);
$phoneNumber1=$results['contacts_phone_1'];
if($phoneNumber1!=null)
{
	array_push($phoneNumbers,$phoneNumber1);
}

$sql="select contacts_phone_2 from contacts2 where openID ='$openid'";
$result = mysqli_query($con, $sql);
if($result==null)
{
	echo "返回空";
}
$results = mysqli_fetch_assoc($result);
$phoneNumber2=$results['contacts_phone_2'];
if($phoneNumber2!=null)
{
	array_push($phoneNumbers,$phoneNumber2);
}

$sql="select contacts_phone_3 from contacts3 where openID ='$openid'";
$result = mysqli_query($con, $sql);
if($result==null)
{
	echo "返回空";
}
$results = mysqli_fetch_assoc($result);
$phoneNumber3=$results['contacts_phone_3'];
if($phoneNumber3!=null)
{
	array_push($phoneNumbers,$phoneNumber3);
}

$sql="select mine_phone from users where open_ID ='$openid'";
$result = mysqli_query($con, $sql);
if($result==null)
{
	echo "返回空";
}
$results = mysqli_fetch_assoc($result);
$user_phone=$results['mine_phone'];
if($user_phone==null)
{
	$sql="select mine_name from users where open_ID ='$openid'";
$result = mysqli_query($con, $sql);
if($result==null)
{
	echo "返回空";
}
$results = mysqli_fetch_assoc($result);
$user_phone=$results['mine_name'];
}
echo $user_phone;
$arrlength=count($phoneNumbers);
echo $arrlength;
echo "<br>";
for($x=0;$x<$arrlength;$x++)
{
    echo $phoneNumbers[$x];
    echo "<br>";
}
for($x=0;$x<$arrlength;$x++){
if($phoneNumbers[$x]!=null){
try {
    $msender = new SmsSingleSender($appid, $appkey);
    $params = [$user_phone];//数组具体的元素个数和模板中变量个数必须一致，例如事例中 templateId:5678对应一个变量，参数数组中元素个数也必须是一个
    $result = $msender->sendWithParam(86, $phoneNumbers[$x],
        $templateId, $params, $smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}
}
}

$sql="UPDATE schedule SET had_send=1 where open_ID='$openid'";
mysqli_query($con, $sql);

;mysqli_query($con, $sql);
;mysqli_close();
mysqli_free_result($result);
mysqli_close($con);
?>