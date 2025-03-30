<?php
namespace App;

require __DIR__ . '/../vendor/autoload.php';

class NewsletterController
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function subscribe($email, $name='')
    {
        $conn = $this->db->getConnection();
        $email = strtolower(trim($email));

        try {
            $stmt = $conn->prepare(query: "SELECT COUNT(*) FROM newsletter WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $stmt = $conn->prepare(query: "SELECT * FROM newsletter WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $result = $stmt->fetchAll();

                if (!empty($result)) {
                    if ($result[0]['status'] == 0) {
                        $updateStmt = $conn->prepare("UPDATE newsletter SET status = 1 WHERE email = :email");
                        $updateStmt->bindParam(':email', $email);
                        $updateStmt->execute();

                        return ["success" => true, "message" => "Cadastro feito na newsletter!"];
                    } else {
                        // Se o status já for 1
                        return ["success" => false, "message" => "Este e-mail já está cadastrado na newsletter!"];
                    }
                }
            }

            $stmt = $conn->prepare("INSERT INTO newsletter (email, nome, status) VALUES (:email, :nome, 1)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':nome', $name);
            $stmt->execute();

            $data = [   
                'header' => 'Novidades da nossa Newsletter',
                'message' => 'Seja bem-vindo ao canal de notícias da Lumcore Systems.',
                'company' => 'Lumcore Systems',
                'unsubscribe_button' => '<hr><a href="http://localhost:5500/?unsubscribe=' . $email . '">Cancelar inscrição</a>'
            ];

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
    public function unsubscribe($email)
    {
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

    public function sendNews($subject, $body)
    {
        // Obtém a conexão com o banco de dados
        $conn = $this->db->getConnection();
        // Consulta os dados da tabela 'newsletter'
        $stmt = $conn->query("SELECT email, nome FROM newsletter WHERE status = 1");

        // Instancia o Mailer para o envio de e-mails
        $mailer = new Mailer();
        $errors = [];

        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $data = [
                'header' => 'Novidades da nossa Newsletter',
                'message' => $body,
                'company' => 'Lumcore Systems',
                'unsubscribe_button' => '<hr><a href="http://localhost:5500/?unsubscribe=' . $row['email'] . '">Cancelar inscrição</a>'
            ];

            $result = $mailer->send($row['email'], $subject, $data, $row['nome']);

            if ($result !== true) {
                $errors[] = $row['email'];  
            }
        }

        return empty($errors)
            ? ["success" => true, "message" => "Newsletter enviada para todos os e-mails!"]
            : ["success" => false, "message" => "Erro ao enviar para: " . implode(', ', $errors)];
    }

}