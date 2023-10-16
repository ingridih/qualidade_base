<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pdo/pdomysql.php';

function Envia_Email($assunto, $mensagem, $email, $anexo){
    
    $Conexao = ConexaoMYSQL::getConnection();
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    $msg = '<html>
            <img src="https://www.diges.com.br/img/logo.png" >
            <h3>DIGES - Desenvolvimento Inteligente de Gestão </h3>
            <br />' . $mensagem . ' 
            <br /> <a href="' . $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"] . '">Diges</a>  
            </html>';

    $mail->IsSMTP(); // Ativar SMTP
    //Server settings
    $mail->Host       = 'smtp.titan.email';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'not.reply@diges.com.br';                     //SMTP username
    $mail->Password   = 'Diges@735502';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    $mail->SetFrom("not.reply@diges.com.br", 'Diges - Not Reply');
    $mail->IsHTML(true);
    $mail->Subject =  mb_convert_encoding($assunto, 'ISO-8859-1', 'UTF-8');
    $mail->Body =  mb_convert_encoding($msg.'<br> Acesse o sistema: <a href="http://qualidade.diges.com.br">Clique Aqui</a>', 'ISO-8859-1', 'UTF-8');

    $email_log = array();
    //EMAIL 
    if (is_array($email)) {
        foreach ($email as $e) {
            $mail->AddAddress($e);
            $email_log[] = $e;
        }
    } else {
        $mail->AddAddress($email);
        $email_log[] = $email;
    }

    $mail->addBCC('contato@diges.com.br');

    
    //ANEXO 
    if ($anexo != null) {
        if (is_array($anexo)) {
            foreach ($anexo as $a) {
                $mail->AddAttachment($a);
            }
        } else {
            $mail->AddAttachment($anexo);
        }
    }

    // ENVIAR EMAIL 
    if (!$mail->Send()) {
        $error = 'Mail error: ' . $mail->ErrorInfo;
        foreach ($email_log as $a) {
            $Conexao->query("INSERT INTO ADM_MAIL_LOG (MAIL_MAIL, MAIL_ASSUNTO, MAIL_CORPO, MAIL_ENVIO, MAIL_ERRO) 
            VALUES ('" . $a . "', '" . $assunto . "', '" . $mensagem . "', '0', '" . $mail->ErrorInfo . "')");
        }
    } else {
        $error = 'Mensagem enviada!';

        foreach ($email_log as $a) {
            $Conexao->query("INSERT INTO ADM_MAIL_LOG (MAIL_MAIL, MAIL_ASSUNTO, MAIL_CORPO, MAIL_ENVIO) 
            VALUES ('" . $a . "', '" . $assunto . "', '" . $mensagem . "', '1')");
        }
    }

}