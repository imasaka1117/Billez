<?php if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Json {
	
	/*
	 * 轉換為json格式
	 * $code	回傳給APP的狀態碼或判別碼, 值為vale為將加密資料轉成json格式[{"vale":"加密文"},{"vale":"加密文"}]
	 * $data	回傳所需的資料
	 */
	public function encode_json($code, $data) {
		switch($code) {
			case 'vale':
				$json = json_encode($data);
				break;
			default:
				$json = json_encode(array("action" => $code,"result" => $data), JSON_UNESCAPED_UNICODE);
				break;
		}
		
		return $json;
	}
	
	/*
	 * 將json格式轉換為其他格式
	 * $code	轉換為其他格式判別碼  值為1是陣列
	 * $data	資料
	 */
	public function decode_json($code, $json) {
		switch($code) {
			case 1:
				$data = json_decode($json, true);
				break;
			default:
				;
				break;
		}
		
		return $data;
	}
}