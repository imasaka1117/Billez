<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Create {
	/*
	 * 產生各種編號
	 * 規則是前兩碼是該種類縮寫加上 AA00001
	 * $prefix	為該ID的前綴字 
	 * $max_id	該種類的最大編號
	 */
	public function id($prefix, $max_id) {
		$id = '';
		$first_word = '';
		$second_word = '';
		$number = '';
	
		//如果還沒有編號,就是AA00001為開始
		if($max_id == '') {
			$id = $prefix . 'AA00001';
		} else {
			//分析$max_id並組合出最大代號+1
			$first_word = substr($max_id, 2, 1);
			$second_word = substr($max_id, 3, 1);
			$number = (int) substr($max_id, 4);
	
			if($number == 99999) {
				$number = 0;
				if(ord($second_word) == 90) {
					$first_word = ord($first_word) + 1;
					$second_word = 65;
				} else {
					$first_word = ord($first_word);
					$second_word = ord($second_word) + 1;
				}
			} else {
				$number++;
				$first_word = ord($first_word);
				$second_word = ord($second_word);
			}
	
			//組合編號
			$id = $prefix . chr($first_word) . chr($second_word) . str_pad($number, 5, 0,STR_PAD_LEFT);
		}
	
		return $id;
	}
	
	/*
	 * 產生傳送簡訊認證碼的功能
	 */
	public function authentication() {
		$authentication_code = "111111";				//認證碼變數
	
		//設定亂數種子
		mt_srand((double)microtime() * 1000000);		
	
		//驗證變數
		$code = "abcdefghijkmnpqrstuvwxy3456789";	
	
		$length = strlen($code);
	
		//亂數取出六個驗證碼給$authentication_code
// 		for($i = 0;$i < 6;$i++) {
// 			$num = rand(0, $length - 1);
// 			$authentication_code .= $code[$num];
// 		}
	
		return $authentication_code;
	}
}