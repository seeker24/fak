<?php
include("index.php");

$user = $_GET['id'];

 $ps = file_get_contents("data/users/$user/uup.txt");

  SendMessage($user,"پرداخت موفق : 4000 تومان");
$m = $ps + 40;
  save("data/users/".$user."/uup.txt",$m);

?>