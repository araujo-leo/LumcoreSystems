<?php
namespace App;

require __DIR__ . '/../vendor/autoload.php';

class NewsletterController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function subscribe($email, $name) {
        $conn = $this->db->getConnection();
        $email = strtolower(trim($email));  
    
        try {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM newsletter WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $count = $stmt->fetchColumn();
    
            if ($count > 0) {
                return ["success" => false, "message" => "Este e-mail já está cadastrado!"];
            }
    
            $stmt = $conn->prepare("INSERT INTO newsletter (email, nome) VALUES (:email, :nome)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':nome', $name);
            $stmt->execute();
    
            $data = [
                'header' => 'Novidades da nossa Newsletter',
                'message' => 'Seja bem-vindo ao canal de notícias da Lumcore Systems.',
                'company' => 'Lumcore Systems',
                'unsubscribe_button' => '<hr><a href="127.0.0.1/unsubscribe?email=' . $email . '">Cancelar inscrição</a>' //organizar aqui
            ];
    
            // Instanciar o objeto Mailer
            $mailer = new Mailer();
    
            if ($mailer->send($email, $data['header'], $data, $name)) {
                return ["success" => true, "message" => "E-mail cadastrado e e-mail de boas-vindas enviado!"];
            } else {
                return ["success" => false, "message" => "E-mail cadastrado, mas não foi possível enviar a mensagem de boas-vindas."];
            }
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Erro ao cadastrar e-mail: " . $e->getMessage()];
        }
    }
    public function unsubscribe($email) {
        $conn = $this->db->getConnection();
    
        try {
            // Remover o e-mail da tabela
            $stmt = $conn->prepare("DELETE FROM newsletter WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                return ["success" => true, "message" => "E-mail removido da newsletter!"];
            } else {
                return ["success" => false, "message" => "E-mail não encontrado na newsletter."];
            }
        } catch (\PDOException $e) {
            return ["success" => false, "message" => "Erro ao remover e-mail: " . $e->getMessage()];
        }
    }

    public function sendNews($subject, $body) {
        $conn = $this->db->getConnection();
        $stmt = $conn->query("SELECT email, nome FROM newsletter --  WHERE bloqueioemail=1 ");
    
        $mailer = new Mailer();
        $errors = [];
    
        $rows = $stmt->fetchAll();
        foreach($rows as $row) {
            $data = [
                'header' => 'Novidades da nossa Newsletter',
                'message' => $body,
                'company' => 'Lumcore Systems',
                'unsubscribe_button' => '<hr><a href="https://example.com/unsubscribe?email=' . urlencode($row['email']) . '">Cancelar inscrição</a>'

            ];
    
            if (!$mailer->send($row['email'], $subject, $data, $row['nome'])) {
                $errors[] = $row['email'];
            }
        }
    
        return empty($errors) ? 
            ["success" => true, "message" => "Newsletter enviada para todos os e-mails!"] :
            ["success" => false, "message" => "Erro ao enviar para: " . implode(', ', $errors)];
    }
}
