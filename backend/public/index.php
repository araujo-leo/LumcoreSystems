<?php


require __DIR__ . '/../vendor/autoload.php';
use App\ContactController;
use App\NewsletterController;

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

        $contact = new ContactController();
        echo json_encode($contact->sendConfirmation($data['email'], $data['name']));
        break;

        case 'subscribe':
            if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(["success" => false, "message" => "E-mail inválido."]);
                exit;
            }
        
            if (!isset($data['name']) || empty(trim($data['name']))) {
                echo json_encode(["success" => false, "message" => "Nome é obrigatório."]);
                exit;
            }
            $newsletter = new NewsletterController();
            echo json_encode($newsletter->subscribe($data['email'], $data['name']));
            break;
    case 'unsubscribe':
        if(!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            echo json_encode(["sucess" => false, "message" => "E-mail inválido."]);
            exit;
        }
        $newsletter = new NewsletterController();
        echo json_encode($newsletter->unsubscribe($data['email']));
        break;

    case 'send-news':
        if (!isset($data['subject']) || !isset($data['body'])) {
            echo json_encode(["success" => false, "message" => "Assunto e mensagem são obrigatórios."]);
            exit;
        }
        $newsletter = new NewsletterController();
        echo json_encode($newsletter->sendNews($data['subject'], $data['body']));
        break;

    default:
        echo json_encode(["success" => false, "message" => "Rota inválida."]);
        exit;
}
