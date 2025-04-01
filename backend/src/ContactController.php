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

    public function sendConfirmation($email, $name, $userMessage)
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
            }else{
                $stmt = $conn->prepare("UPDATE newsletter SET nome = :nome WHERE email = :email");
                $stmt->bindParam(':nome', $name);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
            }

            $body = "Obrigado por entrar em contato! Recebemos sua mensagem e responderemos em breve.";
            $data = [
                'header' => 'Confirmação de envio de mensagem',
                'message' => $body,
                'company' => 'Lumcore Systems',
                'unsubscribe_button' => ''
            ];

            
            $mailer = new Mailer();
            $subjectUser = '=?UTF-8?B?' . base64_encode($data['header']) . '?=';
            $userEmailSent = $mailer->send($email, $subjectUser, $data, $name);

            $body = $userMessage;
            $adminEmail = "lumcore@gmail.com"; 
            $adminMessage = "
                <h3>Nova mensagem recebida!</h3>
                <p><strong>Nome:</strong> $name</p>
                <p><strong>E-mail:</strong> $email</p>
                <p><strong>Mensagem:</strong> $body</p>
            ";
            $adminData = [
                'header' => 'Nova mensagem recebida',
                'message' => $adminMessage,
                'company' => 'Lumcore Systems',
                'unsubscribe_button' => ''
            ];

            $subjectAdmin = '=?UTF-8?B?' . base64_encode("Nova mensagem recebida") . '?=';
            $adminEmailSent = $mailer->send($adminEmail, $subjectAdmin, $adminData, "Admin");

            if ($userEmailSent && $adminEmailSent) {
                return ["success" => true, "message" => "E-mail cadastrado! Confirmação enviada e notificação enviada ao administrador."];
            } elseif ($userEmailSent) {
                return ["success" => false, "message" => "E-mail cadastrado! Confirmação enviada, mas a notificação ao administrador falhou."];
            } else {
                return ["success" => false, "message" => "E-mail cadastrado, mas não foi possível enviar a confirmação e a notificação."];
            }
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Erro ao cadastrar e-mail: " . $e->getMessage()];
        }

    }
}
