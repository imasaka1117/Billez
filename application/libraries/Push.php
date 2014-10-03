<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Push {
	/*
	 * 蒐集需要推播的名單用
	 * $id				要推播的會員編號
	 * $moblie_phone	要推播的會員手機號碼
	 * $moblie_phone_id	要推播的會員手機ID
	 * $billez_code		要推播的帳單編號(用來記錄推播狀態的,某些情況推播用不到就傳入空白'')
	 * $result			推播後的結果1是成功, 2是失敗
	 * $message			推播後給的訊息,可由此知道失敗原因
	 */
	static public $id = array();
	static public $moblie_phone = array();
	static public $moblie_phone_id = array();
	static public $billez_code = array();
	static public $result = array();
	static public $message = array();

	/*
	 * 將要推播的資料丟入推播靜態變數裡
	 * 之後再一次推播
	 * id				資料表
	 * moblie_phone		要查詢欄位
	 * moblie_phone_id	更新條件
	 * billez_code		使用紀錄
	 */
	public function add_static($push) {
		array_push(Push::$id, $push['id']);
		array_push(Push::$moblie_phone, $push['moblie_phone']);
		array_push(Push::$moblie_phone_id, $push['moblie_phone_id']);
		array_push(Push::$billez_code, $push['billez_code']);
		array_push(Push::$result, $push['result']);
		array_push(Push::$message, $push['message']);
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
		Push::$message = array();
	}

	/*
	 * 單一推播整理推播設定資料
	 * 將推播要用到的設定資料準備好
	 * $message	要傳送的訊息
	 */
	public function setting_data($message) {
		//將相同的token剔除
		$uni_moblie_phone_id = array_values(array_unique(Push::$moblie_phone_id));
		
		//依照字符選擇要哪一種推播
		foreach($uni_moblie_phone_id as $moblie_phone_id) {
			if(substr($moblie_phone_id, 0, 2) == 'AP') $this->gcm_push($moblie_phone_id, $message); else $this->apn_push($moblie_phone_id, $message);
		}
	}
	
	/*
	 * 將結果放入靜態變數裡
	 * 注意有相同手機ID的也要放入相同結果
	 * $message		gcm或apn傳的訊息訊息
	 * $push_result	成功或失敗
	 * $token		要比對的字符
	 */
	public function compare_token($message, $push_result, $token) {
		$count = count(Push::$id);
		
		//找到相同的字符,然後加入相對應的結果和訊息
		for($i = 0; $i < $count; $i++) {
			if(Push::$moblie_phone_id[$i] == $token) {
				Push::$result[$i] = $push_result;
				Push::$message[$i] = $message;
			}
		}
	}
	
	/*
	 * 處理推播結果
	 * 將有相同手機ID的會員也丟入相同的推播結果
	 * $kind			apn或gcm
	 * $result			推播後的結果
	 * $moblie_phone_id	手機ID
	 */
	public function handle_result($kind, $result, $moblie_phone_id) {
		if($kind == 'gcm') {
			//把回傳的訊息各放在陣列裡
			if(isset($result['results'][0]['error'])) $message = $result['results'][0]['error'];
			if(isset($result['results'][0]['message_id'])) $message = $result['results'][0]['message_id'];

			//判斷成功或失敗
			if(substr_count($message, ':') >= 1) $push_result = 1; else $push_result = 2;
		} else {
			//apn
		}
		
		$this->compare_token($message, $push_result, $moblie_phone_id);
	}
	
	/*
	 * 
	 */
	public function apn_push($token, $message) {
		
	}
	
	/*
	 * 執行推播函式
	 * 未來可能增加apple的推播
	 * $message 要傳送給手機的訊息
	 */
	public function gcm_push($token, $message) {
		//google api key
		$google_api_key = 'AIzaSyBYJOblFP9_L96Ws8WumtMdqOcT3y7gkqY';
		
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
	
		//執行傳送
		$gcm_result = curl_exec($ch);
	
		//未傳送的錯誤訊息
		if ($gcm_result === false) {
			die('Curl failed: ' . curl_error($ch));
		}
	
		//關閉連結
		curl_close($ch);
	
		//將push回傳的json做解析,並傳入訊息處理
		$this->handle_result('gcm', json_decode($gcm_result, true), $token);
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