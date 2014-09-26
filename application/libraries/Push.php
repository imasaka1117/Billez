<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Push {
	/*
	 * 蒐集需要推播的名單用
	 * $id				要推播的會員編號
	 * $moblie_phone	要推播的會員手機號碼
	 * $moblie_phone_id	要推播的會員手機ID
	 * $billez_code		要推播的帳單編號(用來記錄推播狀態的,某些情況推播用不到就傳入空白'')
	 * $result			推播後的結果1是成功, 2是失敗
	 * $gcm				推播後gcm給的訊息,可由此知道失敗原因
	 */
	static public $id = array();
	static public $moblie_phone = array();
	static public $moblie_phone_id = array();
	static public $billez_code = array();
	static public $result = array();
	static public $gcm = array();
	
	/*
	 * 在這邊做成功訊息的處理
	 * google gcm 目前成功都會有一個:號
	 * 所以以此為當成功
	 * 因為他的成功筆數是整個的
	 * 所以不知道有錯的是哪一筆
	 * 未來加入apple可能會增加或改變
	 */
	public function handle_push_results($push_gcm_results) {
		foreach($push_gcm_results as $push_gcm_result) {
			if(substr_count($push_gcm_result, ':') >= 1) {
				$push_result = 1;
			} else {
				$push_result = 2;
			}
			array_push(Push::$result, $push_result);
			array_push(Push::$gcm, $push_gcm_result);
		}
	}
	
	/*
	 * 若在同一請求下須要做兩次的推播
	 * 則需要先清空當前的靜態變數
	 * 不然會重複推播
	 */
	public function clear_push_list() {
		Push::$id = array();
		Push::$moblie_phone = array();
		Push::$moblie_phone_id = array();
		Push::$billez_code = array();
		Push::$result = array();
		Push::$gcm = array();
	}

	/*
	 * 執行推播函式
	 * 未來可能增加apple的推播
	 * $message 要傳送給手機的訊息
	 */
	public function send_push($message) {
		//傳送欄位,資料內容和要push的手機ID
		$fields = array(
				'registration_ids' => array_values(array_unique(Push::$moblie_phone_id)),
				'data'             => array( 'message' => $message)
		);
		//表頭設定 
		//api key
		$headers = array('Authorization: key=AIzaSyBYJOblFP9_L96Ws8WumtMdqOcT3y7gkqY', 'Content-Type: application/json');
	
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
	
		//執行傳送
		$gcm_result = curl_exec($ch);
	
		//未傳送的錯誤訊息
		if ($gcm_result === false) {
			die('Curl failed: ' . curl_error($ch));
		}
	
		//關閉連結
		curl_close($ch);
	
		//將push回傳的json做解析
		$gcm_result = json_decode($gcm_result);
	
		$push_results = array();
	
		$push_count = count($gcm_ids);
	
		//把回傳的訊息各放在陣列裡
		for($i = 0;$i < $push_count;$i++) {
			if(isset($gcm_result->results[$i]->error)) {
				array_push($push_results, $gcm_result->results[$i]->error);
			}
			if(isset($gcm_result->results[$i]->message_id)) {
				array_push($push_results, $gcm_result->results[$i]->message_id);
			}
		}
	
		$this->handle_push_results($push_results);
	}
	
	/*
	 * 若是有大量推播的話
	 * 會將推播做分組
	 * 因為gcm上限目前是1000個手機ID
	 * 現在先抓300為一組
	 * $num			要推播的總數
	 * $gcm_word	要傳送給手機的訊息
	 * $second 		隔幾秒推播
	 */
	public function group_push_ids($num, $gcm_word, $second) {
		$quotient = $num / 300;
		$remainder = $num % 300;
		$begin = 0;
		$temp_ids = Push::$billez_code;
	
		for($i = 1;$i <= $quotient + 1;$i++) {
			if($i = $quotient + 1) {
				$end = 300 * $i + $remainder;
			} else {
				$end = 300 * $i;
			}
	
			Push::$billez_code = array_slice($temp_ids, $begin, $end - 1);
			$this->send_push($gcm_word);
	
			sleep($second);
			$begin = $end;
		}
	
		Push::$billez_code = $temp_ids;
	}
	
}