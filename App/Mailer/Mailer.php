<?php

/**
 *
 * PHP version 7.2.0
 *
 * @category Bitrix 24
 * @author Itech-Group LLC <nikola@itech-group.ru>
 * @see ____file_see____
 * @since Битрикс 24
 */

namespace App\Mailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

/**
 * Class Mailer
 * PHPMailer wrapper. Sending mail to customer.
 *
 * @package App\Mailer
 */
class Mailer
{

    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->init();
    }

    /**
     * Send notification to customer
     *
     * @param $email
     * @param int $stack_number
     */
    public function sendNotification($email, $stack_number = 0)
    {

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
            echo 'Message could not be sent. Mailer Error:' . $this->mail->ErrorInfo;
            exit;
        }
    }

    /**
     * Server settings
     *
     */
    protected function init()
    {

        // Enable verbose debug output default DEBUG_SERVER
        $this->mail->SMTPDebug = getenv("DEBUG_MODE") ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF ;
        // Send using SMTP
        $this->mail->isSMTP();
        // Set the SMTP server to send through
        $this->mail->Host       = getenv("SMTP_HOST");
        // Enable SMTP authentication
        $this->mail->SMTPAuth   = true;
        // SMTP username
        $this->mail->Username   = getenv('SMTP_USER');
        // SMTP password
        $this->mail->Password   = getenv("SMTP_PASSWORD");
        // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        // TCP port to connect to
        $this->mail->Port       = getenv("SMTP_PORT");
        // Set email format to HTML
        $this->mail->isHTML(true);
        // Set charset
        $this->mail->CharSet = 'UTF-8';
    }
}
