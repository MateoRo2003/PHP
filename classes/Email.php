<?php
namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Email{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token){
        $this->email=$email;
        $this->nombre=$nombre;
        $this->token=$token;
    }

    public function enviarConfirmacion(){
        //crear el objeto de email
        $mail=new PHPMailer(true);
        $mail->SMTPDebug=0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;        
        $mail->Username = 'raviolo05@gmail.com';
        $mail->Password = 'tclc ycsx rxcm ojav'; 
        $mail->SMTPSecure = 'tls'; // Usa TLS
        $mail->Port       = 587;
        $mail->CharSet = 'UTF-8';
    

        $mail->setFrom('bucicardi05@gmail.com','BarberClub');
        $mail->addAddress($this->email);
        $mail->Subject='Confirma tu Cuenta';
        
        //set html
        $mail->isHTML(TRUE);
        $contenido="<html>";
        $contenido.="<p><strong>Hola ".   $this->nombre. "</strong> Has creado tu cuenta en BarberClub, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido.="<p>Presiona aqui: <a href='http://localhost:3000/confirmar-cuenta?token=". $this->token ."'>Confirmar Cuenta</a> </p>";
        $contenido.="<p>Si tu no solicitaste esta cuenta puedes ignorar el mensaje</p>";  
        $contenido.="</html>";

        $mail->Body=$contenido;

        $mail->send();
        // if (!$mail->send()) {
        //     echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
        // } else {
        //     echo 'Correo enviado exitosamente';
        // }
        
    }
    public function enviarInstrucciones(){
        
        $mail=new PHPMailer(true);
        $mail->SMTPDebug=0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;        
        $mail->Username = 'raviolo05@gmail.com';
        $mail->Password = 'tclc ycsx rxcm ojav'; 
        $mail->SMTPSecure = 'tls'; // Usa TLS
        $mail->Port       = 587;
        $mail->CharSet = 'UTF-8';
    

        $mail->setFrom('bucicardi05@gmail.com','BarberClub');
        $mail->addAddress($this->email);
        $mail->Subject='Restablecer Contraseña';


        $contenido="<html>";
        $contenido.="<p><strong>Hola ".   $this->nombre. "</strong> Has solicitado restablecer tu contraseña, sigue el siguiente enlace para hacerlo</p>";
        $contenido.="<p>Presiona aqui: <a href='http://localhost:3000/recuperar?token=". $this->token ."'>Restablecer Contraseña</a> </p>";
        $contenido.="<p>Si tu no solicitaste esta cuenta puedes ignorar el mensaje</p>";  
        $contenido.="</html>";

        $mail->Body=$contenido;

        $mail->send();
    } 
}
    