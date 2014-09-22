<?php

class Route_model extends CI_Model {
	/*
	 * 產生引導資料的起點,判斷是否為第一次請求
	 * 若是則產生引導資料
	 * 若不是檢查手機ID及產生引導資料
	 * $post	所有的POST資料
	 */
	public function index($post) {
		if(!($this->input->post('encode') || $this->input->post('public_key'))) {
			//若請求中沒有這兩個post參數,則回傳錯誤訊息
			$route_data = $this->key->route_data('', array('control_param', 'data'), array('0', '0_000'));
		} elseif($this->input->post('public_key')) {
			//第一次請求,合成APP傳來的公鑰並產生引導資料
			$route_data = $this->merge($post['public_key'], $post['mobile_phone_id'], $post['first']);
		} elseif($this->input->post('id')) {
			//非第一次請求,檢查手機ID是否變更及產生引導資料
			$route_data = $this->check_mobile_phone_id($post['id'], $post['mobile_phone_id'], $post['encode']);	
		} else {
			//非第一次請求之前,解密及產生引導資料
			$route_data = $this->decode_tempdata($post['encode'], $post['mobile_phone_id']);
		}
	}
	
	/*
	 * 處理公鑰及產生所需資料
	 * $public_key		公鑰
	 * $mobile_phone_id	手機ID
	 * $first			引導碼
	 */
	public function merge($public_key, $mobile_phone_id, $first) {
		//將APP給的公鑰合成為Server能用的公鑰
		$public_key = $this->key->merge_key($public_key, $mobile_phone_id, $first);
		//產生引導資料
		$route_data = $this->key->route_data('', array('public_key', 'sub_param', 'mobile_phone_id', 'control_param'), array($public_key, $first . '_1', $mobile_phone_id, $first));
	
		return $route_data;
	}
	
	/*
	 * 解密及產生所需資料
	 * $encode			APP傳來的加密資料
	 * $mobile_phone_id	手機ID
	 */
	public function decode_tempdata($encode, $mobile_phone_id) {
		//查詢函式,通常為五個組合select where other query result
		$sql_select = $this->sql->select(array('private_key'), '');
		$sql_where = $this->sql->where(array('where'), array('mobile_phone_id'), array($mobile_phone_id), array(''));
		$sql_query = $this->query_model->query($sql_select, 'moblie_phone_id_and_key', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		
		/*
		 * 解密APP資料函式
		 * 先將json轉為陣列
		 * 再將資料解密
		 * 解密後的資料也是為json
		 * 所以再將json轉為陣列
		 * 最後產生引導資料
		 */
		$encode_data = $this->json->decode_json(1, $encode);
		$json_data = $this->key->decode_app($encode_data, $sql_result['private_key']);
		$decode_data = $this->json->decode_json(1, $json_data);
		$route_data = $this->key->route_data($decode_data, array('mobile_phone_id', 'private_key'), array($mobile_phone_id, $sql_result['private_key']));
		
		return $route_data;
	}
	
	/*
	 * 檢查手機ID是否不同及產生所需資料
	 * $id				會員編號
	 * $mobile_phone_id	手機ID
	 * $encode			加密資料
	 */
	public function check_mobile_phone_id($id, $mobile_phone_id, $encode) {
		$sql_select = $this->sql->select(array('private_key'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($id), array(''));
		$sql_query = $this->query_model->query($sql_select, 'key', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		
		//更新資料處理,正常為六個一組
		array_push(Sql::$table, 'action_member');
		array_push(Sql::$select, $this->sql->field(array('mobile_phone_id', 'update_user', 'update_time'), array($mobile_phone_id, $id, $this->sql->get_time(1))));
		array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($id), array('')));
		array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $id, 'action_member', '因登入手機ID變更,所以更新手機ID', $this->sql->get_time(1))));
		array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $id, 'action_member', '因登入手機ID變更,所以更新手機ID', $this->sql->get_time(1), '')));
		array_push(Sql::$kind, 2);
		
		//執行更新
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind) === FALSE) {
			/*
			 * 更新失敗處理
			 * 將錯誤訊息轉成json格式
			 * 再將訊息加密
			 * 產生引導資料
			 */
			$json_data = $this->json->encode_json('0_1', '0_101');
			$encode_data = $this->key->encode_app($json_data, $sql_result['private_key']);
			$route_data = $this->key->route_data('', array('control_param', 'data'), array('0', $this->json->encode_json('app', $encode_data)));
			
			return $route_data;
		}
		
		//更新成功處理,解密及產生引導資料
		$encode_data = $this->json->decode_json(1, $encode);
		$json_data = $this->key->decode_app($encode_data, $sql_result['private_key']);
		$decode_data = $this->json->decode_json(1, $json_data);
		$route_data = $this->key->route_data($decode_data, array('mobile_phone_id', 'id', 'private_key'), array($mobile_phone_id, $id, $sql_result['private_key']));
		
		return $route_data;
	}
}