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
	 * 產生傳送亂數編碼的功能
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
	
	/*
	 * 代碼進位判斷
	 * $code	單一代碼
	 * $flag	判斷是否進位
	 */
	public function carry($code, $flag) {
		if($flag) {
			if(ord($code) >= 122) {
				$code = chr(97); 
			} else {
				$code = chr(ord($code) + 1);
			}
		}
		
		return $code;
	}
	
	/*
	 * 產生代碼
	 * $digit	代碼位數
	 * $code	代碼
	 */
	public function code($digit, $code) {
		if($code == '') {
			$init_code = 'a';
			for($i = 0; $i < $digit; $i++) $code = $code . $init_code;

			return $code;
		}

		return $this->handle($code, $digit);
	}
	
	/*
	 * 處理代碼
	 * $code 目前代碼
	 * $digit	代碼位數
	 */
	public function handle($code, $digit) {
		$new_code = '';
		$flag = true;
		$part = array();
		
		for($i = 0; $i < $digit; $i++) $part[$i] = substr($code, $i, 1);
		
		for($i = count($part) - 1; $i >= 0; $i--) {
			$part[$i] = $this->carry($part[$i], $flag);
			if($part[$i] != 'a') $flag = false;
		}
		
		for($i = 0; $i < $digit; $i++) $new_code = $new_code . $part[$i];
			
		return $new_code;
	}
}