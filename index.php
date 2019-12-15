<?php

use App\Actions\LeadAction;
use App\Mailer\Mailer;

include 'vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__FILE__));
$dotenv->load();

$queryUrl = getenv("REST_URL").  getenv("WEBHOOK_ID");

$leadHandler = new LeadAction($queryUrl);

if (
    isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
) {

    if (isset($_REQUEST['id'])) {

        $deleted = $leadHandler->deleteLead($_REQUEST['id']);

    }

    if (isset($_REQUEST['firstname']) && isset($_REQUEST['email'])) {

        $created = $leadHandler->createLead($_REQUEST);

    }

    $list_lead = $leadHandler->listLead();

    if (isset($_REQUEST['message_resend'])) {

        (new Mailer())->sendNotification($_REQUEST['email'], $list_lead['total']);

    }

    echo json_encode($list_lead['result']);
    exit;

} else {

    $list_lead = $leadHandler->listLead();

}



include 'index.phtml';
