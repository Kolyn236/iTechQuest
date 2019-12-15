<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

include 'vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__FILE__));
$dotenv->load();

if(isset($_REQUEST['delete'])){

    $deleted = delete_lead($_REQUEST['delete']);

}

if(isset($_REQUEST['firstname']) && isset($_REQUEST['lastname'])) {

    $created = create_lead($_REQUEST);

    $list_lead = list_lead();

    if(isset($_REQUEST['message_resend'])){
        sendNotification( $_REQUEST['email'] ,$list_lead['total']);
    }

} else {

    $list_lead = list_lead();

}

if (
    isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
) {

    echo json_encode($list_lead['result']);
    exit;
}


/**
 * Delete lead from service
 *
 * @param null $id
 * @return array|bool
 */
function delete_lead($id = null){

    if($id) {
        $queryUrl = getenv("REST_URL").  getenv("WEBHOOK_ID")  .'/crm.lead.delete';
        $queryData = http_build_query([
            'id' => $id
        ]);

        return curl_send_request($queryUrl , $queryData);
    }

    return false;

}

/**
 * Create lead on service
 *
 * @param null $fields
 * @return array
 */
function create_lead($fields = null){

    $queryUrl = getenv("REST_URL").  getenv("WEBHOOK_ID") .'/crm.lead.add';
    $queryData = http_build_query([
        'fields' => array(
            "TITLE" => 'Заявка от ' . $fields['firstname']. ' '. $fields['lastname'],
            "NAME" => $fields['firstname'],
            "LAST_NAME" => $fields['lastname'],
            "STATUS_ID" => "NEW",
            "OPENED" => "Y",
            "ASSIGNED_BY_ID" => 1,
            "CURRENCY_ID"=> "USD",
            "COMMENTS" => $fields['message'],
            "PHONE" => array(array("VALUE" => $fields['phone'], "VALUE_TYPE" => "WORK" )),
            "EMAIL" => array(array("VALUE" => $fields['email'], "VALUE_TYPE" => "WORK" )),
        ),
        'params' => array("REGISTER_SONET_EVENT" => "Y")
    ]);

    $result =  curl_send_request($queryUrl , $queryData);

    if (array_key_exists('error', $result)) {
        echo "Ошибка при сохранении лида: ".$result['error_description']."<br/>";
        exit();
    }

  return $result;

}

/**
 * Send notification to customer
 *
 * @param $email
 * @param int $stack_number
 */
function sendNotification($email, $stack_number = 0){
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = getenv("DEBUG_MODE") ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF ;                      // Enable verbose debug output default DEBUG_SERVER
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = getenv("SMTP_HOST");                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = getenv('SMTP_USER');                     // SMTP username
        $mail->Password   = getenv("SMTP_PASSWORD");                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = getenv("SMTP_PORT");                                    // TCP port to connect to


        //Recipients
        $mail->setFrom('nikolkady@gmail.com', 'iTechQuest');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Ваша заявка была передана специалисту! Ваш номер в очереди ' . $stack_number;
        $mail->Body    = 'Спасибо за Ваше обращение! В скором времени с Вами свяжется специалист нашей организации <Имя Фамилия Ответственного>';
        $mail->AltBody = 'Спасибо за Ваше обращение! В скором времени с Вами свяжется специалист нашей организации <Имя Фамилия Ответственного>';

        $mail->send();


    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; exit;
    }

}

/**
 * List of lead in service
 *
 * @return array
 */
function list_lead(){

    $queryUrl = getenv("REST_URL").  getenv("WEBHOOK_ID") .'/crm.lead.list';
    $queryData = http_build_query([

    ]);
    return curl_send_request($queryUrl, $queryData);
}

/**
 * Send curl query to portal
 *
 * @param $queryUrl
 * @param $queryData
 * @return array
 */
function curl_send_request($queryUrl , $queryData){


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData,
    ));
    $result = curl_exec($curl);
    curl_close($curl);

    return json_decode($result, 1);

}

include 'index.phtml';
