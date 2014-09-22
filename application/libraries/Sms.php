<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Sms {
	//sms函式
	public function authentication_sms($mobile_phone, $authentication_code) {
	
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
