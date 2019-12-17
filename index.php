<?php

use App\Actions\LeadAction;
use App\Mailer\Mailer;

include 'vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__FILE__));
$dotenv->load();

$queryUrl = getenv("REST_URL").  getenv("WEBHOOK_ID");

$leadHandler = new LeadAction($queryUrl);

if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
) {
    if (!empty($_REQUEST['id'])) {
        $deleted = $leadHandler
            ->deleteLead($_REQUEST['id']);
    }

    if (!empty($_REQUEST['firstname']) && !empty($_REQUEST['email'])) {
        $created = $leadHandler
            ->createLead($_REQUEST);
    }

    $listLead = $leadHandler
        ->listLead();

    if (isset($_REQUEST['message_resend'])) {
        (new Mailer())
            ->sendNotification($_REQUEST['email'], $listLead['total']);
    }

    echo json_encode($listLead['result']);
    exit;
} else {
    $listLead = $leadHandler->listLead();
}



include 'public/index.phtml';
