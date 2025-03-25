<?php
namespace App;

use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/../vendor/autoload.php';

class ContactController {
    public function sendConfirmation($email, $name) {
        $mailer = new Mailer();
        $body = "Obrigado por entrar em contato! Recebemos sua mensagem e responderemos em breve.";
        $data = [
            'header' => 'Confirmação de envio de mensagem',
            'message' => $body,
            'company' => 'Lumcore Systems',
            'unsubscribe_button' => ''

        ];

        return $mailer->send($email, $data['header'], $data, $name) ? 
            ["success" => true, "message" => "E-mail enviado com sucesso!"] :
            ["success" => false, "message" => "Erro ao enviar e-mail de confirmação."];
    }
}
