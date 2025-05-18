<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader (created by composer, not included with PHPMailer)
require 'vendor/autoload.php';

class Controller
{

    private string $name;
    private string $email;
    private string $message;
    public $errors;

    function  msgObligatorio($campo): String{
        return "El campo $campo es obligatorio";
    }
    
    function  msgMaxCaracteres($campo): String{
        return "El campo $campo debe tener mas de 2 caracteres";
    }
    
    
    function  enviarEmail():void{
        
        //Create an instance; passing `true` enables exceptions
        
        try {
            //Server settings
            $mail = new PHPMailer();
            
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = '5683a3d101750e';
            $mail->Password = 'e5e04fc973d14f';
            
            
            
            //Recipients
            $mail->setFrom('from@example.com', 'Mailer');
           // $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
            $mail->addAddress('intertelecenter@yahoo.com');               //Name is optional
            $mail->addReplyTo('intertelecenter@yahoo.com', 'Information');
            //$mail->addCC('cc@example.com');
            ///$mail->addBCC('bcc@example.com');
            
            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
            
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body = "<p>Nombre: $this->name</p><p>Email: $this->email</p><p>Mensaje: $this->message</p>";
            $mail->AltBody = "Nombre: $this->name , Email: $this->email , Mensaje: $this->message";
            
            
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    function validar(): array
    {
        $errors = [];
        
        if (empty($this->email)) {
            $errors[] = $this->msgObligatorio("email");
        }else{
            if (! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "El email no es válido";
            }
        }
        
        
        if(empty($this->name)){
            $errors[] = $this->msgObligatorio("nombre");            
        }else{
            if(strlen($this->name) < 2 ){
                $errors[] = $this->msgMaxCaracteres("nombre");
            }
        }
        
        if(empty($this->message)){
            $errors[] =  $this->msgObligatorio("mensaje");
        }else{
            if(strlen($this->message) < 2 ){
                $errors[] = $this->msgMaxCaracteres("mensaje");
            }
        }
       
        if(!empty($errors)){
            return $errors;
        }else{
            return [];
        }
       
    }

    
    
    
    function __construct()
    {
        $this->email = htmlspecialchars($_POST['email']);
        $this->message = htmlspecialchars($_POST['message']);
        $this->name = htmlspecialchars($_POST['name']);
        $this->errors = $this->validar();
        
        if(count($this->errors)==0 ){
            $this->enviarEmail();
        }
        
    }
}

