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
			if($this->input->post('special')) {
				/*
				 * 用於特殊情形,例如需要重啟頁面
				 * 推薦好友 : 1
				 */
				switch ($post['special']) {
					case 1:
						$route_data = $this->key->route_data('', array('control_param', 'sub_param', 'mobile_phone_list', 'id'), array('10', '10_2', $post['mobile_phone_list'], $post['id']));
						break;
				}
			} else {
				//若請求中沒有這兩個post參數,則回傳錯誤訊息
				$route_data = $this->key->route_data('', array('control_param', 'data'), array('0', '0_000'));
			}
		} elseif($this->input->post('public_key')) {
			//第一次請求,合成APP傳來的公鑰並產生引導資料
			$route_data = $this->merge($post['public_key'], $post['mobile_phone_id'], $post['first']);
		} elseif($this->input->post('id')) {
			//非第一次請求,檢查手機ID是否變更及產生引導資料
			$route_data = $this->check_mobile_phone_id($post['id'], $post['mobile_phone_id'], $post['encode']);	
		}  else {
			//非第一次請求之前,解密及產生引導資料
			$route_data = $this->decode_tempdata($post['encode'], $post['mobile_phone_id']);
		}
		
		return $route_data;
	}
	
	/*
	 * 處理公鑰及產生所需資料
	 * $public_key		公鑰
	 * $mobile_phone_id	手機ID
	 * $first			引導碼
	 */
	public function merge($public_key, $mobile_phone_id, $first) {
		//將APP給的公鑰合成為Server能用的公鑰
		//產生引導資料
		return $this->key->route_data('', array('public_key', 'sub_param', 'mobile_phone_id', 'control_param'), array($this->key->merge_key($public_key, $mobile_phone_id, $first), $first . '_1', $mobile_phone_id, $first));
	}
	
	/*
	 * 解密及產生所需資料
	 * $encode			APP傳來的加密資料
	 * $mobile_phone_id	手機ID
	 */
	public function decode_tempdata($encode, $mobile_phone_id) {
		//查詢函式
		$sql_result = $this->sql->result($this->query_model->query($this->sql->select(array('private_key'), ''), 
															'moblie_phone_id_and_key', 
															'', 
															$this->sql->where(array('where'), array('mobile_phone_id'), array($mobile_phone_id), array('')),
															''), 
										 'row_array');
		
		/*
		 * 解密APP資料函式
		 * 先將json轉為陣列
		 * 再將資料解密
		 * 解密後的資料也是為json
		 * 所以再將json轉為陣列
		 * 最後產生引導資料
		 */
		return $this->key->route_data($this->json->decode_json(1, $this->key->decode_app($this->json->decode_json(1, $encode), $sql_result['private_key'])), array('mobile_phone_id', 'private_key'), array($mobile_phone_id, $sql_result['private_key']));
	}
	
	/*
	 * 檢查手機ID是否不同及產生所需資料
	 * $id				會員編號
	 * $mobile_phone_id	手機ID
	 * $encode			加密資料
	 */
	public function check_mobile_phone_id($id, $mobile_phone_id, $encode) {
		//查詢私鑰
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('private_key')), 
																		 'from' => 'key', 
																		 'join'=> '', 
																		 'where' => $this->sql->where(array('where'), array('id'), array($id), array('')), 
																		 'other' => '')), 'row_array');
		
		//更新資料處理
		$this->sql->add_static('action_member', 
							   $this->sql->field(array('mobile_phone_id', 'update_user', 'update_time'), array($mobile_phone_id, $id, $this->sql->get_time(1))), 
							   $this->sql->where(array('where'), array('id'), array($id), array('')), 
							   $this->sql->field(Sql::$user_log, array(1, $id, 'action_member', '因登入手機ID變更,所以更新手機ID', $this->sql->get_time(1))), 
							   $this->sql->field(Sql::$system_log, array(1, $id, 'action_member', '因登入手機ID變更,所以更新手機ID', $this->sql->get_time(1), '')), 
							   2);
		
		//執行更新
		if(!$this->sql->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
			/*
			 * 更新失敗處理
			 * 將錯誤訊息轉成json格式
			 * 再將訊息加密
			 * 產生引導資料
			 */
			return $this->key->route_data('', array('control_param', 'data'), array('0', $this->json->encode_json('app', $this->key->encode_app($this->json->encode_json('0_1', '0_101'), $sql_result['private_key'], ''))));
		}
		
		//更新成功處理,解密及產生引導資料
		return $this->key->route_data($this->json->decode_json(1, $this->key->decode_app($this->json->decode_json(1, $encode), $sql_result['private_key'])), array('mobile_phone_id', 'id', 'private_key'), array($mobile_phone_id, $id, $sql_result['private_key']));
	}
}