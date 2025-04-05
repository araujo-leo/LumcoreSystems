<?php


require __DIR__ . '/../vendor/autoload.php';
use App\ContactController;
use App\NewsletterController;
use App\AuthMiddleware;


error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');


$data = json_decode(file_get_contents('php://input'), true);


// Depuração: Verifica se a requisição POST chegou corretamente
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Método não permitido."]);
    exit;
}

// Depuração: Verifica se a rota foi enviada corretamente
if (!isset($_GET['route'])) {
    echo json_encode(["success" => false, "message" => "Nenhuma rota especificada."]);
    exit;
}

switch ($_GET['route']) {
    case 'contact':
        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["success" => false, "message" => "E-mail inválido."]);
            exit;
        }

        if (!isset($data['name']) || empty(trim($data['name']))) {
            echo json_encode(["success" => false, "message" => "Nome é obrigatório."]);
            exit;
        }
        

         if (!isset($data['message']) || empty(trim($data['message']))) {
            echo json_encode(["success" => false, "message" => "Mensagem é obrigatória."]);
            exit;
        } 

        $contact = new ContactController();
        echo json_encode($contact->sendConfirmation($data['email'], $data['name'], $data['message']));
        break;

    case 'subscribe':
        
        $email = isset($data['email']) ? trim($data['email']) : '';
        
        if(!isset($data['name']) || empty(trim($data['name']))) {
            $data['name'] = "";
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["success" => false, "message" => "E-mail inválido."]);
            exit;
        }

        $newsletter = new NewsletterController();
        echo json_encode($newsletter->subscribe($email, $data['name']));
        break;
    case 'unsubscribe':
        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["sucess" => false, "message" => "E-mail inválido."]);
            exit;
        }
        $newsletter = new NewsletterController();
        echo json_encode($newsletter->unsubscribe($data['email']));
        break;

    case 'send-news':
        $headers = apache_request_headers();
        $token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

        if (!isset($token) && $token != '') {
            echo json_encode(["success" => false, "message" => "Credenciais são exigidas!"]);
            exit;
        }

        if(empty($token)) {
            echo json_encode(["success" => false, "message" => "Credenciais são exigidas!"]);
            exit;
        }

        if (!isset($data['subject']) || !isset($data['body'])) {
            echo json_encode(["success" => false, "message" => "Assunto e mensagem são obrigatórios."]);
            exit;
        }

        $authMiddleware = new AuthMiddleware();
        $authResult = $authMiddleware->verifyToken($token);
        if ($authResult) {
            $storeLogNews = $authMiddleware->logNews($authResult['adminName'], $data['subject'], $data['body']);
            if ($storeLogNews['success'] == true) {
                $newsletter = new NewsletterController();
                echo json_encode($newsletter->sendNews($data['subject'], $data['body']));
                break;
            }
        }



    default:
        echo json_encode(["success" => false, "message" => "Rota inválida."]);
        exit;
}
