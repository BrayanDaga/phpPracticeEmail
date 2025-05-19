<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



// Load Composer's autoloader (created by composer, not included with PHPMailer)
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


class Controller
{
    private String $name;
    private String $email;
    private String $body;
    
    private $errors = [];
    private $msg= [];
    
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getMsg(): array
    {
        return $this->msg;
    }

    function msgObligatorio($campo): String
    {
        return "El campo $campo es obligatorio";
    }

    function msgMaxCaracteres($campo): String
    {
        return "El campo $campo debe tener mas de 2 caracteres";
    }

    function enviarEmail(): void
    {

        // Create an instance; passing `true` enables exceptions
        try {
            // Server settings
            $mail = new PHPMailer();

            // $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
            $mail->isSMTP();
            $mail->Host =  $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['MAIL_PORT'];
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->setFrom($_ENV['MAIL_FROM'], 'Mailer');
            $mail->addAddress($_ENV['MAIL_TO']);

            // Recipients
            $mail->addReplyTo($_ENV['MAIL_TO'], 'Information');
            // $mail->addCC('cc@example.com');
            // /$mail->addBCC('bcc@example.com');

            // Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz'); //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); //Optional name

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Nuevo mensaje desde el formulario de contacto';
            
            $mail->Body = "<h2>Mensaje de contacto</h2>
               <p><strong>Nombre:</strong> $this->name</p>
               <p><strong>Email:</strong> $this->email</p>
               <p><strong>Mensaje:</strong><br>$this->body</p>";
            
            $mail->AltBody = "Nombre: $this->name\nEmail: $this->email\nMensaje:\n$this->body";
            
            $mail->send();
            $this->msg = ['success'=>'Message has been sent'];
        } catch (Exception $e) {
            $this->msg = ['error'=> "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"];
        }
    }

    function validar(): void
    {

        if (empty($this->email)) {
            $this->errors[] = $this->msgObligatorio("email");
        } else {
            if (! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = "El email no es válido";
            }
        }

        if (empty($this->name)) {
            $this->errors[] = $this->msgObligatorio("nombre");
        } else {
            if (strlen($this->name) < 2) {
                $this->errors[] = $this->msgMaxCaracteres("nombre");
            }
        }

        if (empty($this->body)) {
            $this->errors[] = $this->msgObligatorio("mensaje");
        } else {
            if (strlen($this->body) < 2) {
                $this->errors[] = $this->msgMaxCaracteres("mensaje");
            }
        }
    }

    function __construct()
    {
        $this->email = htmlspecialchars($_POST['email']);
        $this->body = htmlspecialchars($_POST['body']);
        $this->name = htmlspecialchars($_POST['name']);
        $this->validar();
        if (count($this->errors) == 0) {
            $this->enviarEmail();
        }
    }
}

