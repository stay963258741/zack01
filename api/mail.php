<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

    require './PHPMailer/src/Exception.php';
    require './PHPMailer/src/PHPMailer.php';
    require './PHPMailer/src/SMTP.php';
    
    function send_mail($data){
        $mail= new PHPMailer();                          //建立新物件
        $mail->IsSMTP();                                    //設定使用SMTP方式寄信
        $mail->SMTPAuth = true;                        //設定SMTP需要驗證
        $mail->SMTPSecure = "ssl";                    // Gmail的SMTP主機需要使用SSL連線
        $mail->Host = "smtp.gmail.com";             //Gamil的SMTP主機
        $mail->Port = 465;                                 //Gamil的SMTP主機的埠號(Gmail為465)。
        $mail->CharSet = "utf-8";                       //郵件編碼
        $mail->Username = "sharemail.service@gmail.com"; //Gamil帳號
        $mail->Password = "sharemail2020";                 //Gmail密碼
        $mail->From = "sharemail.service@gmail.com";        //寄件者信箱
        $mail->FromName = "致勝俱樂部";                  //寄件者姓名
        $mail->Subject =$data['title']; //郵件標題
        $mail->Body = $data['body']; //郵件內容
        $mail->IsHTML(false);                             //郵件內容為html
        $mail->AddAddress($data['email']);            //收件者郵件及名稱
        if(!$mail->Send()){
            //echo "Error: " . $mail->ErrorInfo;
            return false;
        }else{
            return true;
        }
    }
?>