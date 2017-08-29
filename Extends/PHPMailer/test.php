<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->CharSet='UTF-8';                               //设置邮件的字符编码，这很重要，不然中文乱码 
$mail->Host = 'smtp.exmail.qq.com';                   // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'zhiyuan.lin@flamingo-inc.com';     // SMTP username
$mail->Password = 'g6RRpe5q9BdTREfa';                 // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 25;                                     // TCP port to connect to

$mail->setFrom('zhiyuan.lin@flamingo-inc.com', '预警邮件');
$mail->addAddress('347881230@qq.com', 'Joe User');    // Add a recipient
// $mail->addAddress('335220189@qq.com');                // Name is optional
// $mail->addReplyTo('347881230@qq.com', 'Information挂钩');
// $mail->addCC('347881230@qq.com');
// $mail->addBCC('zhiyuan.lin@flamingo-inc.com');
// $mail->addAttachment('D:/vcredist.bmp');              // Add attachments
// $mail->addAttachment('D:/vcredist.bmp', 'new你.jpg'); // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Here is the subject地方';

$mail->Body    = 'This is the HTML message body <b>in bold!公司</br>'.require 'signature.php';
$mail->AltBody = '为了查看该邮件，请切换到支持 HTML 的邮件客户端';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent结果';
}