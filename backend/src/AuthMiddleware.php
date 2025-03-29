<?php
namespace App;
require __DIR__ . '/../vendor/autoload.php';

class AuthMiddleware
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    private static $validAdminTokens = [
        '7W7iaIn4EJabPUIR1Z0eHv2S3R0Fik8Lp5nI5GJlXcx4TvSWybagVaNpamg0AJMT' => "Leonardo", // Leo
        '4OWSeAUQTy1sK85WIiZrv6uT8wpTWgBGJibKarHAOEmkl2eFiEXHgm54pXW52aB4' => "Fernando", // Fernando
        '1kIl8P64FfJ962qpKdWkxd63KVnMnl3qMaXlxmot0PjUp1sd2UL7KMcB2I1IGRCU' => "Ezequiel", // Ezequiel
    ];

    public static function verifyToken($token)
    {
        foreach (self::$validAdminTokens as $adminToken => $adminName) {
            if ($adminToken == $token) {
                return ['token' => $token, 'adminName' => $adminName];
            }
        }
        return false;
    }

    public function logNews($admin, $subject_email, $body_email)
    {
        $result = self::storeLogNews($admin, $subject_email, $body_email);
        if ($result !== true) {
            error_log("Erro ao registrar o log da newsletter: " . $result['message']);
            return $result;
        }
        return ["success" => true, "message" => "Log registrado com sucesso."];
    }


    private function storeLogNews($admin, $subject_email, $body_email)
    {
        try {
            $conn = $this->db->getConnection();

            $stmt = $conn->prepare("INSERT INTO newsletter_logs (admin, subject_email, body_email) VALUES (:admin, :subject_email, :body_email)");
            $stmt->bindParam(':admin', $admin);
            $stmt->bindParam(':subject_email', $subject_email);
            $stmt->bindParam(':body_email', $body_email);

            if ($stmt->execute()) {
                return true;
            } else {
                throw new \Exception("Falha ao inserir log no banco de dados.");
            }
        } catch (\Exception $e) {
            error_log("Erro ao registrar log de newsletter: " . $e->getMessage());
            return ["success" => false, "message" => "Erro ao registrar log de newsletter."];
        }
    }

}
