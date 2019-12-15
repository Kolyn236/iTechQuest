<?php

namespace App\Mailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mailer {

    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->init();
    }

    protected function init()
    {

        //Server settings
        $this->mail->SMTPDebug = getenv("DEBUG_MODE") ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF ;                      // Enable verbose debug output default DEBUG_SERVER
        $this->mail->isSMTP();                                            // Send using SMTP
        $this->mail->Host       = getenv("SMTP_HOST");                    // Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $this->mail->Username   = getenv('SMTP_USER');                     // SMTP username
        $this->mail->Password   = getenv("SMTP_PASSWORD");                               // SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $this->mail->Port       = getenv("SMTP_PORT");                                    // TCP port to connect to
        $this->mail->isHTML(true);                                  // Set email format to HTML
        $this->mail->CharSet = 'UTF-8';
    }

    /**
     * Send notification to customer
     *
     * @param $email
     * @param int $stack_number
     */
    public function sendNotification($email, $stack_number = 0){

        try {

            //Recipients
            $this->mail->setFrom('nikolkady@gmail.com', 'iTechQuest');
            $this->mail->addAddress($email);

            // Content
            $this->mail->Subject = 'Ваша заявка была передана специалисту! Ваш номер в очереди ' . $stack_number;
            $this->mail->Body    = 'Спасибо за Ваше обращение! В скором времени с Вами свяжется специалист нашей организации <Имя Фамилия Ответственного>';
            $this->mail->AltBody = 'Спасибо за Ваше обращение! В скором времени с Вами свяжется специалист нашей организации <Имя Фамилия Ответственного>';

            $this->mail->send();


        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}"; exit;
        }

    }


}