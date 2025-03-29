<?php

namespace App;

require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{

    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->config();
    }

    private function config()
    {
        // Carregar as variáveis do .env
        $dotenv = parse_ini_file(__DIR__ . '/../.env');

        // Configurações do servidor SMTP
        $this->mail->isSMTP();
        $this->mail->Host = $dotenv['SMTP_HOST'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $dotenv['SMTP_USER'];
        $this->mail->Password = $dotenv['SMTP_PASS'];
        $this->mail->SMTPSecure = $dotenv['SMTP_SECURE'];
        $this->mail->Port = $dotenv['SMTP_PORT'];

        $this->mail->setFrom($dotenv['EMAIL_FROM'], $dotenv['EMAIL_FROM_NAME']);
        $this->mail->isHTML(true);
    }

    public function send($to, $subject, $data, $name = '')
    {
        try {
            $template = file_get_contents(__DIR__ . '/views/emails/subscribe.html');

            $template = str_replace("{{name}}", $name, $template);

            foreach ($data as $key => $value) {
                $template = str_replace("{{{$key}}}", $value, $template);
            }

            $this->mail->clearAddresses();

            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body = $template;

            if ($this->mail->send()) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            return "Erro ao enviar e-mail: " . $this->mail->ErrorInfo;
        }
    }


}
