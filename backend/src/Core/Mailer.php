<?php

namespace App\Core;

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

        // Configurações do servidor SMTP
        $this->mail->isSMTP();
        $this->mail->Host = $_ENV['SMTP_HOST'] ?? '';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $_ENV['SMTP_USER'] ?? '';

        $this->mail->Password= $_ENV['SMTP_PASS'] ?? '';

        $this->mail->SMTPSecure = $_ENV['SMTP_SECURE'] ?? '';

        $this->mail->Port = $_ENV['SMTP_PORT'] ?? '';


        $this->mail->setFrom($_ENV['EMAIL_FROM'], $_ENV['EMAIL_FROM_NAME']);
        $this->mail->isHTML(true);
    }

    public function send($to, $subject, $data, $name = '')
    {
        try {
            $template = file_get_contents(__DIR__ . '/../views/emails/subscribe.html');

            $template = str_replace("{{name}}", $name, $template);

            if (empty(trim($name))) {
                $template = str_replace("Olá, <strong></strong>!", "Olá!", $template);
            }

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
