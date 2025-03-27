<?php
namespace App;

use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/../vendor/autoload.php';

class ContactController
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }



    public function sendConfirmation($email, $name)
    {
        $conn = $this->db->getConnection();
        $email = strtolower(trim($email));

        try {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM newsletter WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count == 0) {
                $stmt = $conn->prepare("INSERT INTO newsletter (email, nome, status) VALUES (:email, :nome, 0)");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':nome', $name);
                $stmt->execute();
            }


            $body = "Obrigado por entrar em contato! Recebemos sua mensagem e responderemos em breve.";

            $data = [
                'header' => 'Confirmação de envio de mensagem',
                'message' => $body,
                'company' => 'Lumcore Systems',
                'unsubscribe_button' => '' //<hr><a href="127.0.0.1/confirm?email=' . urlencode($email) . '">Confirmar inscrição</a>
            ];

            $mailer = new Mailer();
            if ($mailer->send($email, $data['header'], $data, $name)) {
                return ["success" => true, "message" => "E-mail cadastrado! Confirmação enviada."];
            } else {
                return ["success" => false, "message" => "E-mail cadastrado, mas não foi possível enviar a confirmação."];
            }
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Erro ao cadastrar e-mail: " . $e->getMessage()];
        }
    }
}
