<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Sms {
	/*
	 * 寄發簡訊
	 * $kind			要寄發的種類用此來判斷送出的內容	1 為認證碼 2為分享帳單
	 * $mobile_phone	要寄發的手機號碼
	 * $data			要傳送的資料
	 */
	public function send_sms($kind, $mobile_phone, $data) {
	
		$result = 1;
	
		return $result;
	}
	
	//分享帳單SMS函式
	public function send_share_bill_sms($mobile_phone, $billez_code, $message) {
		$result = "success";
	
		return $result;
	}
	
	//會員推薦名單SMS函式
	public function send_recommend_sms($name, $mobile_phone) {
		$result = "success";
	
		return $result;
	}
}
