<?php
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");

  $to = $_POST['to'];
  $title = $_POST['title'];
  $message = $_POST['message'];
  $headers = "From: order_dn@dnpk.jp";

  if(mb_send_mail($to, $title, $message, $headers))
  {
    echo "メール送信成功です";
  }
  else
  {
    echo "メール送信失敗です";
  }
 ?>
