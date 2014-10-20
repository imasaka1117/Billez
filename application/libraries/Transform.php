<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');
	
class Transform {
	/*
	 * 各種代碼轉換
	 */
	
	/*
	 * 寄送條件說明
	 */
	public function send_condition($code) {
		switch ($code) {
			case 1:
				$code = '無限制次數寄送實體帳單';
				break;
			case 2:
				$code = '有限制次數寄送實體帳單';
				break;
			case 3:
				$code = '只有寄送實體帳單';
				break;
		}
		
		return $code;
	}
	
	/*
	 * 首頁處理使用
	 */
	public function home_page($code) {
		switch ($code) {
			case 1:
				$code = 'op';
				break;
			case 2:
				$code = 'cs';
				break;
			case 3:
				$code = 'ma';
				break;
		}
	
		return $code;
	}
	
}//class end