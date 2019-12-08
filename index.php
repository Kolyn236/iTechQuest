<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_REQUEST['delete'])){

    $deleted = delete_lead($_REQUEST['delete']);

}

if(isset($_REQUEST['firstname']) && isset($_REQUEST['lastname'])) {

    $created = create_lead($_REQUEST);

}


function delete_lead($id = null){

    $queryUrl = 'https://b24-d2ybw4.bitrix24.ru/rest/1/wlrsr314o80qg8wf/crm.lead.delete';
    $queryData = http_build_query([
        'id' => $id
    ]);


    return curl_send_request($queryUrl , $queryData);

}

function create_lead($fields = null){

    $queryUrl = 'https://b24-d2ybw4.bitrix24.ru/rest/1/wlrsr314o80qg8wf/crm.lead.add';
    $queryData = http_build_query([
        'fields' => array(
            "TITLE" => 'Заявка от ' . $fields['firstname']. ' '. $fields['lastname'],
            "NAME" => $fields['firstname'],
            "LAST_NAME" => $fields['lastname'],
            "STATUS_ID" => "NEW",
            "OPENED" => "Y",
            "ASSIGNED_BY_ID" => 1,
            "CURRENCY_ID"=> "USD",
            "PHONE" => array(array("VALUE" => $fields['phone'], "VALUE_TYPE" => "WORK" )),
            "EMAIL" => array(array("VALUE" => $fields['email'], "VALUE_TYPE" => "WORK" )),
        ),
        'params' => array("REGISTER_SONET_EVENT" => "Y")
    ]);

    return curl_send_request($queryUrl , $queryData);

//    if (array_key_exists('error', $result)) echo "Ошибка при сохранении лида: ".$result['error_description']."<br/>";

}

function list_lead(){

    $queryUrl = 'https://b24-d2ybw4.bitrix24.ru/rest/1/wlrsr314o80qg8wf/crm.lead.list';
    $queryData = http_build_query([
        'id' => 6
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

$list_lead = list_lead();

//echo '<pre>';print_r($list_lead);exit;

include 'index.phtml';
