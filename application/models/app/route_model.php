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
	 * 產生金鑰組並回傳加密公鑰
	 * 因為怕被駭客攔截
	 * 所以使用APP產生的公鑰去加密要給APP的公鑰
	 * $public_key		公鑰
	 * $mobile_phone_id	手機ID
	 * $first			引導碼
	 */
	public function merge($public_key, $mobile_phone_id, $first) {
		$app = $first . '_1';
		
		//新增一對金鑰組
		$key = $this->key->create_key();
		
		//用意是要符合json格式
		$server_public_key['public_key'] = $key['public_key'];
		$json_array = array();
		array_push($json_array, $server_public_key);
		
		//組合APP給的公鑰
		$app_public_key = $this->key->merge_key($public_key);
		
		//查詢手機ID是否存在,若存在則更新金鑰組,不存在則新增一個手機ID及金鑰組
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$mobile_phone_id), ''),
																		 'from' => Table_1::$moblie_phone_id_and_key,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$mobile_phone_id), array($mobile_phone_id), array('')),
																	 	 'other' => '')), 'num_rows');
		if($sql_result) {
			//更新該手機ID金鑰組
			$this->sql->add_static(array('table'=> Table_1::$moblie_phone_id_and_key,
										 'select'=> $this->sql->field(array(Field_2::$private_key, Field_2::$public_key, Field_1::$update_time), array($key['private_key'], $key['public_key'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where'), array(Field_1::$mobile_phone_id), array($mobile_phone_id), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, 1, Table_1::$moblie_phone_id_and_key, '該手機ID存在,更新金鑰組', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, 1, Table_1::$moblie_phone_id_and_key, '該手機ID存在,更新金鑰組', $this->sql->get_time(1), '')),
										 'kind'=> 2));
		} else {
			//新增一個手機ID及金鑰組
			$this->sql->add_static(array('table'=> Table_1::$moblie_phone_id_and_key,
									  	 'select'=> $this->sql->field(array(Field_1::$mobile_phone_id, Field_2::$private_key, Field_2::$public_key, Field_1::$create_time, Field_1::$update_time), array($mobile_phone_id, $key['private_key'], $key['public_key'], $this->sql->get_time(1), $this->sql->get_time(1))),
										 'where'=> '',
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, 1, Table_1::$moblie_phone_id_and_key, '該手機ID不存在,新增該手機ID及金鑰組', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, 1, Table_1::$moblie_phone_id_and_key, '該手機ID不存在,新增該手機ID及金鑰組', $this->sql->get_time(1), '')),
										 'kind'=> 1));
		}
		
		//執行新增/更新,並回傳APP公鑰加密資料
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($app, $json_array), $app_public_key, 'public'));
		} else {
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($app, $first . '_101'), $app_public_key, 'public'));
		}
	}
	
	/*
	 * 解密及產生所需資料
	 * $encode			APP傳來的加密資料
	 * $mobile_phone_id	手機ID
	 */
	public function decode_tempdata($encode, $mobile_phone_id) {
		//查詢該手機ID私鑰
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$private_key), ''),
																	 	 'from' => Table_1::$moblie_phone_id_and_key,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$mobile_phone_id), array($mobile_phone_id), array('')),
																		 'other' => '')), 'row_array');
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
		//查詢該會員金鑰
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$private_key), ''),
																		 'from' => Table_1::$key,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($id), array('')),
																		 'other' => '')), 'row_array');
		$private_key = $sql_result['private_key'];
		//查詢該會員手機ID
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$mobile_phone_id), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($id), array('')),
																		 'other' => '')), 'row_array');
		if($sql_result['mobile_phone_id'] != $mobile_phone_id) {
			//更新資料處理
			$this->sql->add_static(array('table'=> Table_1::$action_member,
										 'select'=> $this->sql->field(array(Field_1::$mobile_phone_id, Field_1::$update_user, Field_1::$update_time), array($mobile_phone_id, $id, $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($id), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $id, Table_1::$action_member, '因登入手機ID變更,所以更新手機ID', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $id, Table_1::$action_member, '因登入手機ID變更,所以更新手機ID', $this->sql->get_time(1), '')),
										 'kind'=> 2));
			//執行更新
			if(!$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
				/*
				 * 更新失敗處理
				 * 將錯誤訊息轉成json格式
				 * 再將訊息加密
			  	 * 產生引導資料
				 */
				return $this->key->route_data('', array('control_param', 'data'), array('0', $this->json->encode_json('app', $this->key->encode_app($this->json->encode_json('0_1', '0_101'), $private_key, ''))));
			}
		}
		
		//若手機ID相同,產生引導資料
		return $this->key->route_data($this->json->decode_json(1, $this->key->decode_app($this->json->decode_json(1, $encode), $private_key)), array('mobile_phone_id', 'id', 'private_key'), array($mobile_phone_id, $id, $private_key));
	}
}