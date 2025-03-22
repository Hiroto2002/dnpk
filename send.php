<?php
header("Content-type: text/plain; charset=UTF-8");
$sender_name = $_POST['namae'];
$sender_mail_address = $_POST['email'];
$contact_body = $_POST['ask'];

//空白チェック
    $errorflag = 0;
	if ($sender_name == null) {
		$error_name = "お名前が未入力です";
		$errorflag = 1;
	}else{$error_name = "";}
	if ($sender_mail_address == null) {
		$error_mail = "メールアドレスが未入力です";
		$errorflag = 1;
	}else{$error_mail = "";}
	if ($contact_body == null) {
		$error_ask = "お問い合わせ内容が未入力です";
		$errorflag = 1;
	}else{$error_ask = "";}
	
if ($errorflag == 1)
{
    if($error_name != ""){ echo $error_name."\n";}
	if($error_mail != ""){ echo $error_mail."\n";}
	if($error_ask != ""){ echo $error_ask."\n";}
    
}
else
{
   //ここにメール送信の設定
   
   echo "送信が完了しました";
}
?>