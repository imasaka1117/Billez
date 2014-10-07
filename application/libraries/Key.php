<?php if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Key {
	/*
	 * 組合完整公鑰及產生導向資料
	 * $public_key			部分公鑰
	 * $mobile_phone_id 	手機ID
	 * $first 				第一次接觸該功能代碼
	 */
	public function merge_key($public_key) {
		$full_public_key 			= "-----BEGIN PUBLIC KEY-----
";
		$full_public_key 			= $full_public_key . $public_key . "
";
		$full_public_key 			= $full_public_key . "-----END PUBLIC KEY-----";
	
		return $full_public_key;
	}
	
	/*
	 * 產生引導資料
	 * $source	原本的陣列資料,若無則傳入空白''
	 * $item	欲設立的名稱  ex: array('control_param', 'data')
	 * $data	資料內容 ex: array('0', '0_000')
	 */
	public function route_data($source, $item, $data) {
		$item_count = count($item);
		
		if($source == '') {
			for($i = 0; $i < $item_count; $i++) $array[$item[$i]] = $data[$i];
		} else {
			for($i = 0; $i < $item_count; $i++) $source[$item[$i]] = $data[$i];
			$array = $source;
		}

		return $array;
	}
	
	/*
	 * 將欲傳給APP的資料分段且加密,以一百字元做分段,再使用base64加密
	 * 使用base64是為了能順利在http傳輸,因為加密後是屬於二進位,不利傳輸
	 * $data		欲加密的json格式資料
	 * $private_key	用來加密的私鑰
	 * $kind		用公鑰加密或私鑰加密 若是公鑰則輸入public
	 */
	public function encode_app($data, $key, $kind) {
		$outer_array = array();
		$array = str_split($data, 100);
		$array_count = count($array);

		for($i = 0;$i < $array_count;$i++) {
			if($kind == 'public') {
				//openssl公鑰加密函式
				openssl_public_encrypt($array[$i], $crypted, $key);				
			} else {
				//openssl私鑰加密函式
				openssl_private_encrypt($array[$i], $crypted, $key);
			}
			
			$json_array['vale'] = base64_encode($crypted);
			array_push($outer_array, $json_array);
		}
		
		return $outer_array;
	}
	
	/*
	 * 將APP傳來的資料做解密,並結合
	 * $encode_data	加密資料
	 * $private_key	用來解密的私鑰
	 */
	public function decode_app($encode_data, $private_key) {
		$encode_data_count 	= count($encode_data);
		$string	= '';
	
		for($i = 0; $i < $encode_data_count; $i++) {
			$encode_data[$i]['vale'] = base64_decode($encode_data[$i]['vale']);	
			//openssl私鑰解密函式
			openssl_private_decrypt($encode_data[$i]['vale'], $decrypted, $private_key);
			$string = $string . $decrypted;
		}
	
		return $string;		
	}
	
	/*
	 * 產生金鑰組
	 * $config					產生金鑰設定
	 * openssl_pkey_new			產生金鑰
	 * openssl_pkey_export		將私鑰轉換為字串
	 * openssl_pkey_get_details	取出公鑰
	 */
	public function create_key() {
		$config = array( 'config' => $_SERVER['OPENSSL_CONF'], 'private_key_bits' => 1024, 'private_key_type' => OPENSSL_KEYTYPE_RSA );
		$key = openssl_pkey_new($config);				
		openssl_pkey_export($key, $private_Key, null, $config);
		$public_key = openssl_pkey_get_details($key);			
		
		return array('private_key' => $private_Key, 'public_key' => $public_key['key']);
	}
}