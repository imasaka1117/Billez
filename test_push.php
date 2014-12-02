<?php

ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE);

//google api key
$google_api_key = 'AIzaSyBYJOblFP9_L96Ws8WumtMdqOcT3y7gkqY';

$token = 'APA91bFnApQ1KWynAgGdZFEHzaEJ0Qf4W7cSK7sfPVQx-JVvzc8qTEZZuSUG_TdFRF3ZSXf1JC1qeKY9E8y7h-x11bX4RMQ7b8baRAEBsyLO4MM11w17NQE-yoQzmUmQXxg5RBc7FufL2sO9Cq2JhRHno1oudBOXOA';

//傳送欄位,資料內容和要push的手機ID
$fields = array('registration_ids' => array($token), 'data' => array( 'message' => $message));

//表頭設定
$headers = array('Authorization: key=' . $google_api_key, 'Content-Type: application/json');

//開啟連結
$ch = curl_init();

//設定網址,傳送變數和資料
//傳送到GCM的網址
curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
echo 'dddddddddddddd';
//執行傳送
$gcm_result = curl_exec($ch);

//未傳送的錯誤訊息
if ($gcm_result === false) {
	die('Curl failed: ' . curl_error($ch));
}
echo print_r($gcm_result);
echo curl_error($ch);
//關閉連結
curl_close($ch);