<?php
ob_start();
include 'confing.php';
/*
به نام خداوند جان و خرد
*/
function makereq($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datas));
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
//-----####-----------
function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }
  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }
  foreach ($parameters as $key => &$val) {
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = "https://api.telegram.org/bot".API_KEY."/".$method.'?'.http_build_query($parameters);
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  return exec_curl_request($handle);
}
$update = json_decode(file_get_contents('php://input'));
var_dump($update);
// ------------------
$chat_id = $update->message->chat->id;
$message_id = $update->message->message_id;
$from_id = $update->message->from->id;
$name = $update->message->from->first_name;
$username = $update->message->from->username;
$textmessage = isset($update->message->text)?$update->message->text:'';
//rusian panel
$panels = scandir('data/panels');
$countp = count($panels);
$randp = rand(1,$countp);
$arrayp = $panels[$randp];
$rupanel = file_get_contents("data/panels/$arrayp");
$uup = file_get_contents("data/users/$from_id/uup.txt");
$command = file_get_contents("data/users/$from_id/step.txt");
$code_taiid = file_get_contents('data/users/'.$from_id."/code taiid.txt");
$number = file_get_contents('data/users/'.$from_id."/number.txt");
$banlist = file_get_contents("data/banlist.txt");
// ------------------
function SendMessage($ChatId, $TextMsg)
{
 makereq('sendMessage',[
'chat_id'=>$ChatId,
'text'=>$TextMsg,
'parse_mode'=>"MarkDown"
]);
}
function Forward($KojaShe,$AzKoja,$KodomMSG)
{
makereq('ForwardMessage',[
'chat_id'=>$KojaShe,
'from_chat_id'=>$AzKoja,
'message_id'=>$KodomMSG
]);
}
function save($filename,$TXTdata)
	{
	$myfile = fopen($filename, "w") or die("Unable to open file!");
	fwrite($myfile, "$TXTdata");
	fclose($myfile);
	}
// ------------------
    if (strpos($block , "$from_id") !== false) {
	return false;
	}
elseif ($textmessage == '/start') {
  if (!file_exists("data/users/$from_id/uup.txt")) {
    mkdir("data/users/$from_id");
    save("data/users/$from_id/uup.txt","0");
	save("data/users/$from_id/pid.txt","0");
    save("data/users/$from_id/step.txt","none");
    save("data/users/$from_id/ln.txt","0");
	save("data/users/$from_id/am.txt","0");
	save("data/users/$from_id/app.txt","0");
	save("data/users/$from_id/sh.txt","0");
    save('data/users/'.$from_id."/code taiid.txt","0");
    save('data/users/'.$from_id."/number.txt","0");
    $members = file_get_contents("Member.txt");
    save("Member.txt",$members."$from_id\n");
 var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"ثبت نام شما با موفقیت انجام شد.",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
      'keyboard'=>[
              [
              ['text'=>"خرید شماره مجازی"],['text'=>"پنل شماره"]
              ],
			  [
              ['text'=>"دیلیت کردن شماره"]
              ],
			  [
              ['text'=>"مشخصات من"],['text'=>"افزایش اعتبار"]
              ],
			  [
              ['text'=>"راهنما"],['text'=>"پشتیبانی"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
}
 var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"شما در ربات عضو بودید.",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
      'keyboard'=>[
              [
              ['text'=>"خرید شماره مجازی"],['text'=>"پنل شماره"]
              ],
			  [
              ['text'=>"دیلیت کردن شماره"]
              ],
			  [
              ['text'=>"مشخصات من"],['text'=>"افزایش اعتبار"]
              ],
			  [
              ['text'=>"راهنما"],['text'=>"پشتیبانی"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
}
	elseif($update->message->contact and $number == null){
	$rand = rand(11111,55555);
	$ce = $rand;
	file_put_contents('data/users/'.$from_id."/code taiid.txt",$ce);
	file_put_contents('data/users/'.$from_id."/step.txt","taiid nashode");
	file_put_contents('data/users/'.$from_id."/number.txt",$update->message->contact->phone_number);
	SendMessage($chat_id,"📢 برای فعال سازی اکانت کاربری خود , کد $ce را وارد کنید !");
	}
	//================
	elseif($command == "taiid nashode"){
	if($textmessage == $code_taiid){
	file_put_contents('data/users/'.$from_id."/step.txt","none");
 var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"🌐 کد کاربری شما تایید شد !
 به پنل خود خوش آمدید ✅",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
      'keyboard'=>[
              [
              ['text'=>"خرید شماره مجازی"],['text'=>"پنل شماره"]
              ],
			  [
              ['text'=>"دیلیت کردن شماره"]
              ],
			  [
              ['text'=>"مشخصات من"],['text'=>"افزایش اعتبار"]
              ],
			  [
              ['text'=>"راهنما"],['text'=>"پشتیبانی"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
	}else{
	SendMessage($chat_id,"کد تایید اشتباه است , کد را صحیح وارد نمایید ❕");
	}
	}
	//===============
  elseif($textmessage == '🔍 چرا باید اکانت کاربریم تایید بشه ؟'){
  file_put_contents('data/users/'.$from_id."/step.txt","none");
  SendMessage($chat_id,"⚠️ دلایل :

❇️ تقلب نشدن در اعمال ربات
❇️ شناسایی کاربران
❇️ بالابردن قوت تشخیص کاربران
❇️ امنیت بالا و ...");
  }
	//================
	elseif($number == null){
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"⚠️ حتما باید اکانت کاربری شما توسط شماره تایید شود !",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
      'keyboard'=>[
              [
	          ['text'=>"✅ تایید اکانت",'request_contact'=>true]
              ],
			  [
              ['text'=>"🔍 چرا باید اکانت کاربریم تایید بشه ؟"]
              ]
			  ],
            'resize_keyboard'=>true
        ])
    ]));
}
	//===============
elseif ($textmessage == "بازگشت"){
	  file_put_contents('data/users/'.$from_id."/step.txt","none");
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"به منوی اصلی برگشتیم.",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
      'keyboard'=>[
              [
              ['text'=>"خرید شماره مجازی"],['text'=>"پنل شماره"]
              ],
			  [
              ['text'=>"دیلیت کردن شماره"]
              ],
			  [
              ['text'=>"مشخصات من"],['text'=>"افزایش اعتبار"]
              ],
			  [
              ['text'=>"راهنما"],['text'=>"پشتیبانی"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
}
//------#######-----------
    elseif($textmessage == "خرید شماره مجازی"){
	  var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"اپلیکیشن را انتخاب کنید",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
                'keyboard'=>[
			    [
			    ['text'=>"تلگرام"],['text'=>"گوگل"]
			  ],
			   [
			    ['text'=>"بیتالک"],['text'=>"اینستاگرام"]
			  ],
			  [
			   ['text'=>"واتس اپ"],['text'=>"لاین"]
			  ],
			  [
			   ['text'=>"بازگشت"]
			  ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
}
//--------------------
elseif ($textmessage == "تلگرام") {
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
        'text'=>"کشور را انتخاب کنید",
        'parse_mode'=>'Html',
        'reply_markup'=>json_encode([
              'keyboard'=>[
			    [
			    ['text'=>"چین"],['text'=>"فیلیپین"]
			  ],
			   [
			    ['text'=>"ویت نام"],['text'=>"مالزی"],['text'=>"میانمار"]
			  ],
			  [
			   ['text'=>"کامبوج"],['text'=>"اندونزی"]
			  ],
			  [
			   ['text'=>"بازگشت"]
			  ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
	file_put_contents('data/users/'.$from_id."/app.txt","10");
    }
	//--------------------
elseif ($textmessage == "اینستاگرام") {
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
        'text'=>"کشور را انتخاب کنید",
        'parse_mode'=>'Html',
        'reply_markup'=>json_encode([
              'keyboard'=>[
			    [
			    ['text'=>"چین"],['text'=>"فیلیپین"]
			  ],
			   [
			    ['text'=>"ویت نام"],['text'=>"مالزی"],['text'=>"میانمار"]
			  ],
			  [
			   ['text'=>"کامبوج"],['text'=>"اندونزی"]
			  ],
			  [
			   ['text'=>"بازگشت"]
			  ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
	file_put_contents('data/users/'.$from_id."/app.txt","8");
    }
	//--------------------
elseif ($textmessage == "بیتالک") {
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
        'text'=>"کشور را انتخاب کنید",
        'parse_mode'=>'Html',
        'reply_markup'=>json_encode([
              'keyboard'=>[
			    [
			    ['text'=>"چین"],['text'=>"فیلیپین"]
			  ],
			   [
			    ['text'=>"ویت نام"],['text'=>"مالزی"],['text'=>"میانمار"]
			  ],
			  [
			   ['text'=>"کامبوج"],['text'=>"اندونزی"]
			  ],
			  [
			   ['text'=>"بازگشت"]
			  ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
	file_put_contents('data/users/'.$from_id."/app.txt","9");
    }
	//--------------------
elseif ($textmessage == "واتس اپ") {
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
        'text'=>"کشور را انتخاب کنید",
        'parse_mode'=>'Html',
        'reply_markup'=>json_encode([
              'keyboard'=>[
			    [
			    ['text'=>"چین"],['text'=>"فیلیپین"]
			  ],
			   [
			    ['text'=>"ویت نام"],['text'=>"مالزی"],['text'=>"میانمار"]
			  ],
			  [
			   ['text'=>"کامبوج"],['text'=>"اندونزی"]
			  ],
			  [
			   ['text'=>"بازگشت"]
			  ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
	file_put_contents('data/users/'.$from_id."/app.txt","3");
    }
	//--------------------
elseif ($textmessage == "لاین") {
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
        'text'=>"کشور را انتخاب کنید",
        'parse_mode'=>'Html',
        'reply_markup'=>json_encode([
              'keyboard'=>[
			    [
			    ['text'=>"چین"],['text'=>"فیلیپین"]
			  ],
			   [
			    ['text'=>"ویت نام"],['text'=>"مالزی"],['text'=>"میانمار"]
			  ],
			  [
			   ['text'=>"کامبوج"],['text'=>"اندونزی"]
			  ],
			  [
			   ['text'=>"بازگشت"]
			  ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
	file_put_contents('data/users/'.$from_id."/app.txt","4");
    }
	//--------------------
elseif ($textmessage == "گوگل") {
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
        'text'=>"کشور را انتخاب کنید",
        'parse_mode'=>'Html',
        'reply_markup'=>json_encode([
              'keyboard'=>[
			    [
			    ['text'=>"چین"],['text'=>"فیلیپین"]
			  ],
			   [
			    ['text'=>"ویت نام"],['text'=>"مالزی"],['text'=>"میانمار"]
			  ],
			  [
			   ['text'=>"کامبوج"],['text'=>"اندونزی"]
			  ],
			  [
			   ['text'=>"بازگشت"]
			  ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
	file_put_contents('data/users/'.$from_id."/app.txt","1");
    }
//------#######--------
//numbers
elseif ($textmessage == "چین"){
	      if ($uup >= 20) {
$app = file_get_contents('data/users/'.$from_id."/app.txt");
$tcn = file_get_contents("http://www.getsmscode.com/do.php?action=getmobile&username=$user&token=$tok&pid=$app");
save("data/users/$from_id/ln.txt",$tcn);
save("data/users/$from_id/sh.txt","$tcn");
 var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"شماره شما ساخته شد ، شماره :
		  $tcn
		  بعد از دو دقیقه برای دریافت کد کلیک کنید",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
         'keyboard'=>[
			    [
			    ['text'=>"چین - کد"]
			  ]
            ],
            'resize_keyboard'=>true
        ])
		    ]));
		  }
	 else {
$jsonu = json_decode(file_get_contents("http://pay.iteam-co.ir/wsdlgo/?amount=20000&apikey=$api_key&apitype=1&callback=https://$twentyp?id=$from_id"));
$clickpay = $jsonu->message;
SendMessage($chat_id,"فاکتور - شماره مجازی
قیمت : 2000 تومان
لینک پرداخت :
http://pay.iteam-co.ir/pay/$clickpay
بعد از پرداخت بصورت خودکار محصول را تحویل میگیرید !");
      }
	  }

elseif ($textmessage == "چین - کد"){
$nm = file_get_contents("data/users/$from_id/ln.txt");
$app = file_get_contents('data/users/'.$from_id."/app.txt");
$gcn = file_get_contents("http://www.getsmscode.com/do.php?action=getsms&username=$user&token=$tok&pid=$app&mobile=$nm");
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"کد شما :
		  $gcn",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
          'keyboard'=>[
              [
              ['text'=>"خرید شماره مجازی"],['text'=>"پنل شماره"]
              ],
			  [
              ['text'=>"دیلیت کردن شماره"]
              ],
			  [
              ['text'=>"مشخصات من"],['text'=>"افزایش اعتبار"]
              ],
			  [
              ['text'=>"راهنما"],['text'=>"پشتیبانی"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
	    $ps = file_get_contents("data/users/$from_id/uup.txt");
    settype($ps,"integer");
    $nps = $ps - 20;
    save("data/users/$from_id/uup.txt",$nps);
	save("data/users/$from_id/ln.txt","0");
						 $la = file_get_contents("data/a.txt");
    settype($la,"integer");
    $new = $la + 1;
    save("data/a.txt",$new);	}
		  

elseif ($textmessage == "فیلیپین"){
	      if ($uup >= 40) {
$app = file_get_contents('data/users/'.$from_id."/app.txt");
$tcn = file_get_contents("http://www.getsmscode.com/do.php?action=getmobile&username=$user&token=$tok&pid=$app&cocode=ph");
save("data/users/$from_id/ln.txt","$tcn");
save("data/users/$from_id/sh.txt","$tcn");
 var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"شماره شما ساخته شد ، شماره :
		  $tcn
		  بعد از دو دقیقه برای دریافت کد کلیک کنید",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
         'keyboard'=>[
			    [
			    ['text'=>"فیلیپین - کد"]
			  ]
            ],
            'resize_keyboard'=>true
        ])
		    ]));
		  }
	 else {
$jsonu = json_decode(file_get_contents("http://pay.iteam-co.ir/wsdlgo/?amount=40000&apikey=$api_key&apitype=1&callback=https://site.com/40.php?id=$from_id"));
$clickpay = $jsonu->message;
SendMessage($chat_id,"فاکتور - شماره مجازی
قیمت : 4000 تومان
لینک پرداخت :
http://pay.iteam-co.ir/pay/$clickpay
بعد از پرداخت بصورت خودکار محصول را تحویل میگیرید !");
      }
	  }

elseif ($textmessage == "فیلیپین - کد"){
$nm = file_get_contents("data/users/$from_id/ln.txt");
$app = file_get_contents('data/users/'.$from_id."/app.txt");
$gcn = file_get_contents("http://www.getsmscode.com/do.php?action=getsms&username=$user&token=$tok&pid=$app&mobile=$nm&cocode=ph");
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"کد شما :
		  $gcn",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
     'keyboard'=>[
              [
              ['text'=>"خرید شماره مجازی"],['text'=>"پنل شماره"]
              ],
			  [
              ['text'=>"دیلیت کردن شماره"]
              ],
			  [
              ['text'=>"مشخصات من"],['text'=>"افزایش اعتبار"]
              ],
			  [
              ['text'=>"راهنما"],['text'=>"پشتیبانی"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
	    $ps = file_get_contents("data/users/$from_id/uup.txt");
    settype($ps,"integer");
    $nps = $ps - 40;
    save("data/users/$from_id/uup.txt",$nps);
	save("data/users/$from_id/ln.txt","0");
						 $la = file_get_contents("data/a.txt");
    settype($la,"integer");
    $new = $la + 1;
    save("data/a.txt",$new);		  }
		  
elseif ($textmessage == "ویت نام"){
	      if ($uup >= 40) {
$app = file_get_contents('data/users/'.$from_id."/app.txt");
$tcn = file_get_contents("http://www.getsmscode.com/do.php?action=getmobile&username=$user&token=$tok&pid=$app&cocode=vn");
save("data/users/$from_id/ln.txt","$tcn");
save("data/users/$from_id/sh.txt","$tcn");
 var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"شماره شما ساخته شد ، شماره :
		  $tcn
		  بعد از دو دقیقه برای دریافت کد کلیک کنید",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
         'keyboard'=>[
			    [
			    ['text'=>"ویت نام - کد"]
			  ]
            ],
            'resize_keyboard'=>true
        ])
		    ]));
		  }
		 else {
$jsonu = json_decode(file_get_contents("http://pay.iteam-co.ir/wsdlgo/?amount=40000&apikey=$api_key&apitype=1&callback=https://$twentyp?id=$from_id"));
$clickpay = $jsonu->message;
SendMessage($chat_id,"فاکتور - شماره مجازی
قیمت : 4000 تومان
لینک پرداخت :
http://pay.iteam-co.ir/pay/$clickpay
بعد از پرداخت بصورت خودکار محصول را تحویل میگیرید !");
      }
	  }

elseif ($textmessage == "ویت نام - کد"){
$app = file_get_contents('data/users/'.$from_id."/app.txt");
$nm = file_get_contents("data/users/$from_id/ln.txt");
$gcn = file_get_contents("http://www.getsmscode.com/do.php?action=getsms&username=$user&token=$tok&pid=$app&mobile=$nm&cocode=vn");
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"کد شما :
		  $gcn",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
        'keyboard'=>[
              [
              ['text'=>"خرید شماره مجازی"],['text'=>"پنل شماره"]
              ],
			  [
              ['text'=>"دیلیت کردن شماره"]
              ],
			  [
              ['text'=>"راهنما"],['text'=>"پشتیبانی"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
	    $ps = file_get_contents("data/users/$from_id/uup.txt");
    settype($ps,"integer");
    $nps = $ps - 40;
    save("data/users/$from_id/uup.txt",$nps);
	save("data/users/$from_id/ln.txt","0");
						 $la = file_get_contents("data/a.txt");
    settype($la,"integer");
    $new = $la + 1;
    save("data/a.txt",$new);		  }
		  
		  elseif ($textmessage == "مالزی"){
	      if ($uup >= 60) {
$app = file_get_contents('data/users/'.$from_id."/app.txt");
$tcn = file_get_contents("http://www.getsmscode.com/do.php?action=getmobile&username=$user&token=$tok&pid=$app&cocode=my");
save("data/users/$from_id/ln.txt","$tcn");
save("data/users/$from_id/sh.txt","$tcn");
 var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"شماره شما ساخته شد ، شماره :
		  $tcn
		  بعد از دو دقیقه برای دریافت کد کلیک کنید",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
         'keyboard'=>[
			    [
			    ['text'=>"مالزی - کد"]
			  ]
            ],
            'resize_keyboard'=>true
        ])
		    ]));
		  }
		 else {
$jsonu = json_decode(file_get_contents("http://pay.iteam-co.ir/wsdlgo/?amount=60000&apikey=$api_key&apitype=1&callback=https://$twentyp?id=$from_id"));
$clickpay = $jsonu->message;
SendMessage($chat_id,"فاکتور - شماره مجازی
قیمت : 6000 تومان
لینک پرداخت :
http://pay.iteam-co.ir/pay/$clickpay
بعد از پرداخت بصورت خودکار محصول را تحویل میگیرید !");
      }
	  }


$jsonu = json_decode(file_get_contents("http://pay.iteam-co.ir/wsdlgo/?amount=50000&apikey=$api_key&apitype=1&callback=https://$twentyp?id=$from_id"));
$clickpay = $jsonu->message;
SendMessage($chat_id,"فاکتور - شماره مجازی
قیمت : 6000 تومان
لینک پرداخت :
http://pay.iteam-co.ir/pay/$clickpay
بعد از پرداخت بصورت خودکار محصول را تحویل میگیرید !");
      }
	  }

elseif ($textmessage == "میانمار - کد"){
$nm = file_get_contents("data/users/$from_id/ln.txt");
$app = file_get_contents('data/users/'.$from_id."/app.txt");
$gcn = file_get_contents("http://www.getsmscode.com/do.php?action=getsms&username=$user&token=$tok&pid=$app&mobile=$nm&cocode=mm");
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"کد شما :
		  $gcn",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
  'keyboard'=>[
              [
              ['text'=>"خرید شماره مجازی"],['text'=>"پنل شماره"]
              ],
			  [
              ['text'=>"دیلیت کردن شماره"]
              ],
			  [
              ['text'=>"راهنما"],['text'=>"پشتیبانی"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
	    $ps = file_get_contents("data/users/$from_id/uup.txt");
    settype($ps,"integer");
    $nps = $ps - 60;
    save("data/users/$from_id/uup.txt",$nps);
	save("data/users/$from_id/ln.txt","0");
					 $la = file_get_contents("data/a.txt");
    settype($la,"integer");
    $new = $la + 1;
    save("data/a.txt",$new);		  }
		  
 elseif ($textmessage == "کامبوج"){
	      if ($uup >= 40) {
$app = file_get_contents('data/users/'.$from_id."/app.txt");
$tcn = file_get_contents("http://www.getsmscode.com/do.php?action=getmobile&username=$user&token=$tok&pid=$app&cocode=kh");
save("data/users/$from_id/ln.txt","$tcn");
save("data/users/$from_id/sh.txt","$tcn");
 var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"شماره شما ساخته شد ، شماره :
		  $tcn
		  بعد از دو دقیقه برای دریافت کد کلیک کنید",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
         'keyboard'=>[
			    [
			    ['text'=>"کامبوج - کد"]
			  ]
            ],
            'resize_keyboard'=>true
        ])
		    ]));
		  }
		 else {
$jsonu = json_decode(file_get_contents("http://pay.iteam-co.ir/wsdlgo/?amount=40000&apikey=$api_key&apitype=1&callback=https://$twentyp?id=$from_id"));
$clickpay = $jsonu->message;
SendMessage($chat_id,"فاکتور - شماره مجازی
قیمت :4000 تومان
لینک پرداخت :
http://pay.iteam-co.ir/pay/$clickpay
بعد از پرداخت بصورت خودکار محصول را تحویل میگیرید !");
      }
	  }

elseif ($textmessage == "کامبوج - کد"){
$nm = file_get_contents("data/users/$from_id/ln.txt");
$app = file_get_contents('data/users/'.$from_id."/app.txt");
$gcn = file_get_contents("http://www.getsmscode.com/do.php?action=getsms&username=$user&token=$tok&pid=$app&mobile=$nm&cocode=kh");
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"کد شما :
		  $gcn",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
  'keyboard'=>[
              [
              ['text'=>"خرید شماره مجازی"],['text'=>"پنل شماره"]
              ],
			  [
              ['text'=>"دیلیت کردن شماره"]
              ],
			  [
              ['text'=>"مشخصات من"],['text'=>"افزایش اعتبار"]
              ],
			  [
              ['text'=>"راهنما"],['text'=>"پشتیبانی"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
	    $ps = file_get_contents("data/users/$from_id/uup.txt");
    settype($ps,"integer");
    $nps = $ps - 40;
    save("data/users/$from_id/uup.txt",$nps);
						 $la = file_get_contents("data/a.txt");
    settype($la,"integer");
    $new = $la + 1;
    save("data/a.txt",$new);
}		  
		   elseif ($textmessage == "اندونزی"){
	      if ($uup >= 40) {
$app = file_get_contents('data/users/'.$from_id."/app.txt");
$tcn = file_get_contents("http://www.getsmscode.com/do.php?action=getmobile&username=$user&token=$user&pid=$app&cocode=id");
save("data/users/$from_id/ln.txt","$tcn");
save("data/users/$from_id/sh.txt","$tcn");
 var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"شماره شما ساخته شد ، شماره :
		  $tcn
		  بعد از دو دقیقه برای دریافت کد کلیک کنید",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
         'keyboard'=>[
			    [
			    ['text'=>"اندونزی - کد"]
			  ]
            ],
            'resize_keyboard'=>true
        ])
		    ]));
		  }
			 else {
$jsonu = json_decode(file_get_contents("http://pay.iteam-co.ir/wsdlgo/?amount=40000&apikey=$api_key&apitype=1&callback=https://$twentyp?id=$from_id"));
$clickpay = $jsonu->message;
SendMessage($chat_id,"فاکتور - شماره مجازی
قیمت : 4000 تومان
لینک پرداخت :
http://pay.iteam-co.ir/pay/$clickpay
بعد از پرداخت بصورت خودکار محصول را تحویل میگیرید !");
      }
	  }

elseif ($textmessage == "اندونزی - کد"){
$nm = file_get_contents("data/users/$from_id/ln.txt");
$app = file_get_contents('data/users/'.$from_id."/app.txt");
$gcn = file_get_contents("http://www.getsmscode.com/do.php?action=getsms&username=$user&token=$tok&pid=$app&mobile=$nm&cocode=id");
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"کد شما :
		  $gcn",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
  'keyboard'=>[
              [
              ['text'=>"خرید شماره مجازی"],['text'=>"پنل شماره"]
              ],
			  [
              ['text'=>"دیلیت کردن شماره"]
              ],
			  [
              ['text'=>"مشخصات من"],['text'=>"افزایش اعتبار"]
              ],
			  [
              ['text'=>"راهنما"],['text'=>"پشتیبانی"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
	    $ps = file_get_contents("data/users/$from_id/uup.txt");
    settype($ps,"integer");
    $nps = $ps - 40;
    save("data/users/$from_id/uup.txt",$nps);
						 $la = file_get_contents("data/a.txt");
    settype($la,"integer");
    $new = $la + 1;
    save("data/a.txt",$new);
}
//number panel
elseif ($textmessage == "پنل شماره"){
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"انتخاب کنید",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
  'keyboard'=>[
              [
              ['text'=>"خرید پنل روسیه"]
              ],
			  [
              ['text'=>"بازگشت"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
}
		   elseif ($textmessage == "خرید پنل روسیه"){
			   if ($countp == 0) {
  SendMessage($chat_id,"پنل های روسیه به اتمام رسیده اند به زودی تعدادی افزووده میشوند");
			   }else {
 var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"اطلاعات پنل شماره مجازی روسیه:\nقیمت: 4 هزار تومان\nتعداد شماره: 10\nاز سایت: sms-reg.com\nتحویل فوری: بله\n\nایا اطمینان به خرید دارید؟",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
         'keyboard'=>[
			    [
			    ['text'=>"بله"]
			  ],
			  	[
			    ['text'=>"بازگشت"]
			  ]
            ],
            'resize_keyboard'=>true
        ])
		    ]));
		  }
		   }
		   elseif ($textmessage == "بله"){
	      if ($uup >= 40) {
 var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"پنل خریداری شد.\nکد پنل ۱۰ تایی شما:\n$rupanel",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
         'keyboard'=>[
              [
              ['text'=>"خرید شماره مجازی"],['text'=>"پنل شماره"]
              ],
			  [
              ['text'=>"دیلیت کردن شماره"]
              ],
			  [
              ['text'=>"مشخصات من"],['text'=>"افزایش اعتبار"]
              ],
			  [
              ['text'=>"راهنما"],['text'=>"پشتیبانی"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
	$p = file_get_contents("data/p.txt");
	$pr = $p + 1;
	    save("data/p.txt",$pr);
		unlink(data/panels/$arrayp);
		}
			 else {
$jsonu = json_decode(file_get_contents("http://pay.iteam-co.ir/wsdlgo/?amount=40000&apikey=$api_key&apitype=1&callback=https://$twentyp?id=$from_id"));
$clickpay = $jsonu->message;
SendMessage($chat_id,"فاکتور - پنل ۱۰ تایی شماره روسیه
قیمت : 4000 تومان
لینک پرداخت :
http://pay.iteam-co.ir/pay/$clickpay
بعد از پرداخت بصورت خودکار محصول را تحویل میگیرید !");
      }
	  }
//delete number
    elseif($textmessage == 'دیلیت کردن شماره'){
  file_put_contents('data/users/'.$from_id."/step.txt","dele");
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"اخرین شماره ی دریافتی خود را از این ربات بفرستید تا دلیت شود.",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
  'keyboard'=>[
              [
              ['text'=>"بازگشت"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
}
  elseif($command == 'dele'){
	    $sh = file_get_contents("data/users/$from_id/sh.txt");
  if($textmessage == $sh){
  $del = file_get_contents("http://www.getsmscode.com/do.php?action=addblack&username=$user&token=$tok&pid=$app&mobile=$sh");
  file_put_contents('data/users/'.$from_id."/step.txt","none");
  SendMessage($chat_id,"شماره دیلیت شد!\nپیغام مرکز:\n$del");
  }else{
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"این شماره اخرین شماره ی شما نیست.",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
         'keyboard'=>[
              [
              ['text'=>"خرید شماره مجازی"],['text'=>"پنل شماره"]
              ],
			  [
              ['text'=>"دیلیت کردن شماره"]
              ],
			  [
              ['text'=>"مشخصات من"],['text'=>"افزایش اعتبار"]
              ],
			  [
              ['text'=>"راهنما"],['text'=>"پشتیبانی"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
}
  save("data/users/$from_id/step.txt","none");
  }
//help
	   elseif ($textmessage == "راهنما"){ 
  SendMessage($chat_id,"راهنما:\nشما با استفاده از این ربات هوشمند شماره مجازی کشور ها مختلف را به صورت ارزان خریدار کنید.");
  }
  //profile
  	   elseif ($textmessage == 'مشخصات من'){  
  SendMessage($chat_id,"ایدی شما: $chat_id\nموجودی شما: $uup");
  }
  //buy
    	   elseif ($textmessage == 'افزایش اعتبار'){
$twen = json_decode(file_get_contents("http://pay.iteam-co.ir/wsdlgo/?amount=20000&apikey=$api_key&apitype=1&callback=https://$twentyp?id=$from_id"));
$click20 = $twen->message;
$for = json_decode(file_get_contents("http://pay.iteam-co.ir/wsdlgo/?amount=40000&apikey=$api_key&apitype=1&callback=https://$twentyp?id=$from_id"));
$click40= $for->message;
$six = json_decode(file_get_contents("http://pay.iteam-co.ir/wsdlgo/?amount=60000&apikey=$api_key&apitype=1&callback=https://$twentyp?id=$from_id"));
$click60 = $six->message;
  SendMessage($chat_id,"برای افزایش اعتبار خود و خرید شماره مجازی از طریق لینک های زیر اقدام کنید\n\nمبلغ دو هزار تومان:\nhttp://pay.iteam-co.ir/pay/$click20\nمبلغ چهار هزار تومان:\nhttp://pay.iteam-co.ir/pay/$click40\nمبلغ شش هزار تومان:\nhttp://pay.iteam-co.ir/pay/$click60\n\nتوجه: بعد از پرداخت بصورت خودکار سکه ها واریز میشوند.");
  }
  //support
    elseif($textmessage == 'پشتیبانی'){
  save('data/users/'.$from_id."/step.txt","contact");
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"پیغام خود را برای پشتیبانی ارسال کنید",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
  'keyboard'=>[
              [
              ['text'=>"بازگشت"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
}
  elseif($command == 'contact'){
  if($textmessage){
  file_put_contents('data/users/'.$from_id."/step.txt","none");
  SendMessage($chat_id,"پیام شما ثبت شد و بزودی جواب داده میشود ✅");
  if($from_username == null){
  $from_username = '---';
  }else{
  $from_username = "@$from_username";
  }
  SendMessage($admin,"
کاربری با مشخصات : 
$from_id
@$username
یک پیام به شما ارسال کرد ✅
متن پیام :
 $textmessage");
  }else{
  SendMessage($chat_id,"`فقط متن میتوانید ارسال کنید ❎ .`");
  }
  save("data/users/$from_id/step.txt","none");
  }
	//panel
	   elseif ($textmessage == '/panel' and $from_id == $admin){
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"خوش امدید ادمین ، یک گزینه را انتخاب کنید :",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
         'keyboard'=>[
			    [
			    ['text'=>"امار ربات"],['text'=>"امار فروش"]
			  ],
			  [
			  ['text'=>"فوروارد همگانی"],['text'=>"پیام همگانی"]
			  ],
			  [
			  ['text'=>"افزودن پنل شماره"],['text'=>"اطلاعات حساب"]
			  ],
			  [
			  ['text'=>"پیگیری کاربر"],['text'=>"بلاک و انبلاک"]
			  ],
			  [
			  ['text'=>"بلاک لیست"]
			  ],
			  [
			  ['text'=>"سکه دادن به کاربر"],['text'=>"گرفتن سکه از کاربر"]
			  ],
			  [
			  ['text'=>"بازگشت"]
			  ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
	   }
	       elseif($textmessage == 'پیگیری کاربر' and $from_id == $admin){
  save('data/users/'.$from_id."/step.txt","find");
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
          'text'=>"ایدی عددی فرد مورد نظر را برای برسی ارسال کنید",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
  'keyboard'=>[
              [
              ['text'=>"بازگشت"]
              ]
            ],
            'resize_keyboard'=>true
        ])
    ]));
}
  elseif($command == 'find'){
	  $id = $textmessage;
  if (file_exists("data/users/$id")) {
	$num = file_get_contents('data/users/'.$id."/number.txt");  
  file_put_contents('data/users/'.$from_id."/step.txt","none");
  SendMessage($admin,"
شماره ی کاربر مورد نظر:\n+$num");
  }else{
  SendMessage($chat_id,"کاربر در سیستم موجود نیست.");
  }
  save("data/users/$from_id/step.txt","none");
  }
	   	   elseif ($textmessage == 'اطلاعات حساب' and $from_id == $admin){  
		   $numbers = file_get_contents("http://www.getsmscode.com/do.php?action=login&username=$user&token=$tok");
  SendMessage($chat_id,"امار کل حساب ما در سایت گت اس ام اس کد:\n$numbers\n\nراهنما:\nیوزر|بالانس|شماره های گرفته شده|بالانس خرج شده");
    }
		   	   elseif ($textmessage == 'بلاک و انبلاک' and $from_id == $admin){  
		   $numbers = file_get_contents("http://www.getsmscode.com/do.php?action=login&username=$user&token=$tok");
  SendMessage($chat_id,"برای بلاک کردن متخلفان از ربات به صورت زیر استفاده کنید:\n/block id\nو برای انبلاک:\n/unblock id");
    }
	     	   elseif ($textmessage == 'سکه دادن به کاربر'){  
  SendMessage($chat_id,"برای ارسال سکه به کاربر از دستور زیر استفاده کنید:\n/addcoin id count");
  }
  	     	   elseif ($textmessage == 'گرفتن سکه از کاربر'){  
  SendMessage($chat_id,"برای گرفتن سکه از کاربر از دستور زیر استفاده کنید:\n/getcoin id count");
  }
  elseif (strpos($textmessage,"/addcoin") !== false && $from_id == $admin) {
  $text = explode(" ",$textmessage);
  if ($text['2'] != "" && $text['1'] != "") {
    $coin = file_get_contents("data/users/".$text['1']."/coin.txt");
    settype($coin,"integer");
    $newcoin = $coin - $text['2'];
    save("data/users/".$text['1']."/coin.txt",$newcoin);
    SendMessage($chat_id,"عملیات فوق با موفقیت انجام شد");
    SendMessage($text['1'],"ادمین به شما ".$text['2']." سکه ارسال کرد.");
  }
  else {
    SendMessage($chat_id,"Syntax Error!");
  }
}
elseif (strpos($textmessage,"/getcoin") !== false && $from_id == $admin) {
  $text = explode(" ",$textmessage);
  if ($text['2'] != "" && $text['1'] != "") {
    $coin = file_get_contents("data/users/".$text['1']."/coin.txt");
    settype($coin,"integer");
    $newcoin = $coin - $text['2'];
    save("data/users/".$text['1']."/coin.txt",$newcoin);
    SendMessage($chat_id,"عملیات فوق با موفقیت انجام شد");
    SendMessage($text['1'],"ادمین از شما ".$text['2']." الماس کم کرد");
  }
  else {
    SendMessage($chat_id,"Syntax Error!");
  }
}
	   elseif ($textmessage == 'امار ربات' and $from_id == $admin){  
	  $mems = file_get_contents('Member.txt');
    $member_id = explode("\n",$mems);
    $mmemcount = count($member_id) -1;
  SendMessage($chat_id,"تعداد کاربران : $mmemcount");
  }
  
  	   elseif ($textmessage == 'افزودن پنل شماره' and $from_id == $admin){  
  SendMessage($chat_id,"برای افزودن پنل شماره مجازی از طریق زیر اقدام کنید:\n/addpanel text1 text2\ntext1 = نام فایل مثلا:\ntest\ntext2 = متن فایل (کد پنل روس)");
  }
  elseif (strpos($textmessage,"/addpanel") !== false && $from_id == $admin) {
  $text = explode(" ",$textmessage);
  if ($text['2'] != "" && $text['1'] != "") {
    save("data/panels/".$text['1'].".txt",$text['2']);
    SendMessage($chat_id,"عملیات فوق با موفقیت انجام شد");
  }
  else {
    SendMessage($chat_id,"Syntax Error!");
  }
}
elseif (strpos($textmessage , "/block") !== false && $chat_id == $admin) {
$result = str_replace("/block ","",$textmessage);
save("data/banlist.txt",$banlist."\n".$result);
SendMessage($chat_id,"شخص مورد نظر بلاک شد.");
SendMessage($result,"*You Are Blocked From Admin.* ");
}
elseif (strpos($textmessage , "/unblock") !== false && $chat_id == $admin) {
$result = str_replace("/unblock ","",$textmessage);
$blist = str_replace($result,"",$banlist);
save("data/banlist.txt",$blist);
SendMessage($chat_id,"شخص مورد نظر انبلاک شد");
SendMessage($result,"You Are *unBlocked* From Admin. ");
}
 elseif($textmessage == 'بلاک لیست' and $from_id == $admin){
SendMessage($chat_id,"بلاک شده های ربات : \n$banlist");
}
    elseif ($textmessage == 'فوروارد همگانی' and $from_id == $admin){
	save("data/users/".$from_id."/step.txt","fwd");
	SendMessage($chat_id,"پیام خود را فوروارد کنید :");
	}
	elseif($command == 'fwd' and $from_id == $admin){
	save("data/users/".$from_id."/step.txt","none");
	SendMessage($chat_id,"در حال ارسال ...");
	$all_member = fopen( "Member.txt", 'r');
		while(!feof($all_member)) {
 			$user = fgets($all_member);
			Forward($user,$admin,$message_id);
		}
	}
 elseif ($textmessage == 'امار فروش' and $from_id == $admin){  
$fo = file_get_contents("data/a.txt");
$p = file_get_contents("data/p.txt");
SendMessage($chat_id,"تعداد شماره های فروش رفته تا این لحظه:\n$fo\nتعداد پنل های فروش رفته تا این لحظه:\n$p");
 }
    elseif ($textmessage == 'پیام همگانی' and $from_id == $admin){
	save("data/users/".$from_id."/step.txt","sto");
	SendMessage($chat_id,"پیام را بفرستید.");
	}
	elseif($command == 'sto' and $from_id == $admin){
	save("data/users/".$from_id."/step.txt","none");
	SendMessage($chat_id,"در حال ارسال ...");
	$all_member = fopen( "Member.txt", 'r');
		while(!feof($all_member)) {
 			$user = fgets($all_member);
			SendMessage($user,$textmessage);
		}
	}
  else {
    SendMessage($chat_id,"");
  }unlink("error_log");
//by @bots_sudo
?>