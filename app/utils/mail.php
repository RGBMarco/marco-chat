<?php
    namespace App\Utils;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require_once(__DIR__."/../../vendor/autoload.php");
    class Mail {
        public static function sendMail($title,$content,array $peers) {
            $mail = new PHPMailer(true);
            //$mail->SMTPOptions = array( 'ssl' => array( 'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true ) );
            $mail->SMTPDebug = 1;
            $mail->isSMTP();
            $mail->Host = "smtp.mxhichina.com";
            $mail->SMTPAuth = true;
            $mail->Username = "chat@rgbmarco.top";
            $mail->Password = ;
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->Charset = "UTF-8";
            $mail->setFrom('chat@rgbmarco.top','Marco');
            $mail->isHTML(true);
            $mail->Body = $content;
           // $mail->addAddress('wh989565@foxmail.com');
            //$mail->addAddress('577482975@qq.com');
            foreach ($peers as $k => $v) {
                $mail->addAddress($v);
            }
            $mail->Subject = $title;
            return $mail->send();
        }
    }
?>