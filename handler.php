<?php
include("index.php");
$update = json_decode(file_get_contents('php://input'));

$userid = $update->CustomField;
$title = $update->Title;
$code = $update->Code;
$name = $update->Name;
$amount = $update->Amount;
$coin1 = file_get_contents("data/users/".$userid."/uup.txt");

if ($title == "2000coin") {
  SendMessage($userid,"شما با موفقیت مبلغ $amount را پرداخت کردید
و ۲۰۰۰ تومان اعتبار گرفتید");
$m = $coin1 + 20;
  save("data/users/".$userid."/uup.txt",$m);
}

if ($title == "4000coin") {
  SendMessage($userid,"شما با موفقیت مبلغ $amount را پرداخت کردید
و ۴۰۰۰ هزار تومان اعتبار گرفتید");
$m = $coin1 + 40;
  save("data/users/".$userid."/uup.txt",$m);
}

if ($title == "6000coin") {
  SendMessage($userid,"شما با موفقیت مبلغ $amount را پرداخت کردید
و ۶۰۰۰ هزار تومان اعتبار گرفتید");
$m = $coin1 + 2400;
  save("data/users/".$userid."/uup.txt",$m);
}
?>