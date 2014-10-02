<?php

class Join_model extends CI_Model {
	/*
	 * 加入會員起點函式
	 * $route_data從APP來的參數
	 */
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '1_2':
				return $this->check_account($route_data);
				break;
			case '1_3':
				return $this->send_password($route_data);
				break;
			case '1_4':
				return $this->send_authentication($route_data);
				break;
			case '1_5':
				return $this->send_again($route_data);
				break;
			case '1_6':
				return $this->check_authentication($route_data);
				break;
		}
	}
	
	/*
	 * 新增會員
	 * 被check_account函式使用
	 * $kind		判斷新增一般或第三方會員
	 * $route_data	新增所需資料
	 * $third_id	第三方資料
	 */
	public function insert_member($key, $route_data, $third_id) {
		//查詢目前最大會員編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$id . ') AS max'), 'function'),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//產生會員編號
		$id = $this->create->id('AC', $sql_result['max']);

		//新增行動會員
		$this->sql->add_static(array('table'=> Table_1::$action_member,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$email, Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone, Field_1::$mobile_phone_id, Field_1::$state, Field_1::$fb_id, Field_1::$google_id, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($id, $route_data['email'], '', '', '', $route_data['mobile_phone_id'], 1, $third_id['fb_id'], $third_id['google_id'], $id, $this->sql->get_time(1), $id, $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $id, Table_1::$action_member, '加入會員_新增行動會員初始資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $id, Table_1::$action_member, '加入會員_新增行動會員初始資料', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//新增金鑰
		$this->sql->add_static(array('table'=> Table_1::$key,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_2::$private_key, Field_2::$public_key, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($id, $key['private_key'], $key['public_key'], $id, $this->sql->get_time(1), $id, $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $id, Table_1::$key, '加入會員_新增金鑰資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $id, Table_1::$key, '加入會員_新增金鑰資料', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//新增密碼(預先新增空密碼)
		$this->sql->add_static(array('table'=> Table_1::$password,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$password, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($id, '', $id, $this->sql->get_time(1), $id, $this->sql->get_time(1))),
								 	 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $id, Table_1::$password, '加入會員_新增密碼資料(預先新增)', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $id, Table_1::$password, '加入會員_新增密碼資料(預先新增)', $this->sql->get_time(1), '')),
									 'kind'=> 1));
							
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			//組合回傳資料
			$json_array = array();
			$temp_array['id'] = $id;
			$temp_array['public_key'] = $key['public_key'];
			array_push($json_array, $temp_array);
			return $json_array;
		} else {
			//回傳錯誤代碼1_206
			return $route_data['sub_param'] . '03';
		}
	}
	
	/*
	 * 整理回傳資料
	 * $id	會員編號
	 * $public_key	會員公鑰
	 */
	public function return_data($id, $public_key) {
		$json_array = array();
		//組合回傳資料
		$temp_array['id'] = $id;
		$temp_array['public_key'] = $public_key;
		array_push($json_array, $temp_array);
		
		return $json_array;
	}
	
	/*
	 * 組合select 第三方類型
	 * $third_party	包含第三方參數
	 */
	public function select_third_party($third_party, $id) {
		if($third_party['google_id'] != '') {
			$field = Field_1::$google_id;
			$value = $third_party['google_id'];
		}
		if($third_party['fb_id'] != '') {
			$field = Field_1::$fb_id;
			$value = $third_party['fb_id'];
		}
		
		$select['field'] = array($field, Field_1::$update_user, Field_1::$update_time);
		$select['value'] = array($value, $id, $this->sql->get_time(1), $id, $this->sql->get_time(1));
		
		return $select;
	}
	
	/*
	 * 檢查帳號狀態及初始化或重複申請判斷
	 * 被check_accountg使用
	 * $kind		判斷新增一般或第三方會員
	 * $route_data	新增所需資料 
	 * $third_id	第三方資料
	 */
	public function check_state($key, $route_data, $third_id) {
		//檢查該帳號狀態
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$state, Field_1::$id), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$email), array($route_data['email']), array('')),
																		 'other' => '')), 'row_array');
		$id = $sql_result['id'];
		$state = $sql_result['state'];		
		
		//檢查該帳號狀態
		if($state == 1 || $state == 3) {
			//初始化行動會員基本資料
			$this->sql->add_static(array('table'=> Table_1::$action_member,
										 'select'=> $this->sql->field(array(Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone, Field_1::$mobile_phone_id, Field_1::$state, Field_1::$fb_id, Field_1::$google_id, Field_1::$update_user, Field_1::$update_time), array('', '', '', $route_data['mobile_phone_id'], 1, $third_id['fb_id'], $third_id['google_id'], $id, $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($id), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $id, Table_1::$action_member, '加入會員_初始化行動會員基本資料', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $id, Table_1::$action_member, '加入會員_初始化行動會員基本資料', $this->sql->get_time(1), '')),
										 'kind'=> 2));
			//初始化金鑰
			$this->sql->add_static(array('table'=> Table_1::$key,
										 'select'=> $this->sql->field(array(Field_2::$private_key, Field_2::$public_key, Field_1::$update_user, Field_1::$update_time), array($key['private_key'], $key['public_key'], $id, $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($id), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $id, Table_1::$key, '加入會員_初始化行動會員金鑰組', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $id, Table_1::$key, '加入會員_初始化行動會員金鑰組', $this->sql->get_time(1), '')),
										 'kind'=> 2));
			//初始化密碼
			$this->sql->add_static(array('table'=> Table_1::$password,
										 'select'=> $this->sql->field(array(Field_1::$password, Field_1::$update_user, Field_1::$update_time), array('', $id, $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($id), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $id, Table_1::$password, '加入會員_初始化行動會員密碼', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $id, Table_1::$password, '加入會員_初始化行動會員密碼', $this->sql->get_time(1), '')),
										 'kind'=> 2));
			//執行
			if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
				//組合回傳資料
				return $this->return_data($id, $key['public_key']);
			} else {
				//回傳錯誤代碼1_203
				return $route_data['sub_param'] . '03';
			}
		} else {
			if($third_id['google_id'] == '' && $third_id['fb_id'] == '') {
				//查詢該帳號是否有第三方資料,有的話則是已有第三方而新增一般
				$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$fb_id, Field_1::$google_id), ''),
																				 'from' => Table_1::$action_member,
																				 'join'=> '',
																				 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($id), array('')),
																				 'other' => '')), 'row_array');
				if($sql_result['fb_id'] == '' && $sql_result['google_id'] == '') {
					//沒有則代表一般申請重複
					//回傳錯誤代碼1_202
					return $route_data['sub_param'] . '02';
				}
			} else {
				//查詢該第三方碼是否有重複
				$third_party_repeat = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																				 'from' => Table_1::$action_member,
																				 'join'=> '',
																				 'where' => $this->sql->where(array('where', 'or_where'), array(Field_1::$fb_id, Field_1::$google_id), array($third_id['third_party'], $third_id['third_party']), array('')),
																				 'other' => '')), 'num_rows');
				if($third_party_repeat) {
					//代表第三方申請重複
					//回傳錯誤代碼1_201
					return $route_data['sub_param'] . '01';
				}
				
				$select = $this->select_third_party($third_id, $id);
				
				//將一般帳號更新第三方資料
				$this->sql->add_static(array('table'=> Table_1::$action_member,
											 'select'=> $this->sql->field($select['field'], $select['value']),
											 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($id), array('')),
											 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $id, Table_1::$action_member, '加入會員_已有一般會員用第三方加入', $this->sql->get_time(1))),
											 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $id, Table_1::$action_member, '加入會員_已有一般會員用第三方加入', $this->sql->get_time(1), '')),
											 'kind'=> 2));
				//執行
				if(!$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
					//回傳錯誤代碼1_203
					return $route_data['sub_param'] . '03';
				}
			}
			//查詢該帳號公鑰
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$public_key), ''),
																			 'from' => Table_1::$key,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($id), array('')),
																			 'other' => '')), 'row_array');
			//組合回傳資料
			return $this->return_data($id, $sql_result['public_key']);
		}
	}
	
	/*
	 * 處理第三方參數
	 * $route_data 所需參數資料
	 */
	public function third_party($route_data) {
		//第三方參數
		$google_id = '';
		$fb_id = '';
		$third_party = '';
		
		if(isset($route_data['google_id'])) {
			$google_id = $route_data['google_id'];
			$third_party = $route_data['google_id'];
		}
		if(isset($route_data['fb_id'])) {
			$fb_id = $route_data['fb_id'];
			$third_party = $route_data['fb_id'];
		}
		
		return array('google_id' => $google_id, 'fb_id' => $fb_id, 'third_party' => $third_party);
	}
	
	/*
	 * 檢查帳號狀態及傳回公鑰
	 * 以及是否有選擇第三方登入																			
	 * 必須確認該帳號是否存在																				-有->回傳已有一般帳號申請第三方帳號
	 * 若有則合併該帳號															-已申請成功->檢查是否有密碼存在
	 * 流程:																							-無->回傳該第三方帳號已申請過
	 * 													-有->檢查該第三方帳號狀態
	 * 					-有->用第三方參數檢查是否有該第三方存在						-未申請成功->初始化該第三方帳號資料
	 * 																			-有->檢查該第三方帳號狀態
	 * 													-無->檢查電子郵件是否有重複
	 * 檢查是否有第三方參數															-無->新增第三方會員`
	 * 																								-有->回傳已有第三方帳號申請一般帳號
	 * 																-已申請成功->檢查該帳號是否有第三方資料
	 * 										-有->檢查該一般會員帳號狀態									-無->回傳該一般帳號已申請過
	 * 																-未申請成功->初始化該一般帳號資料
	 * 					-無->檢查該帳號是否重複
	 * 
	 * 										-無->新增一般會員`
	 * $route_data 所需參數
	 */
	public function check_account($route_data) {
		//產生金鑰組
		$key = $this->key->create_key();
		$third_id = $this->third_party($route_data);
		
		
		//檢查電子郵件是否已經存在
		$exist = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$email), array($route_data['email']), array('')),
																		 'other' => '')), 'num_rows');	
		//檢查是否有第三方參數
		if(isset($route_data['google_id']) || isset($route_data['fb_id'])) {
			//檢查該第三方資料是否存在
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																			 'from' => Table_1::$action_member,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where', 'or_where'), array(Field_1::$fb_id, Field_1::$google_id), array($third_id['third_party'], $third_id['third_party']), array('')),
																			 'other' => '')), 'num_rows');
			if(!$sql_result && !$exist) {
				//不存在則新增第三方會員
				return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->insert_member($key, $route_data, $third_id)), $route_data['private_key'], ''));
			}
		} else {
			//沒有第三方參數
			//不存在則新增一般會員
			if(!$exist) return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->insert_member($key, $route_data, $third_id)), $route_data['private_key'], ''));
		}

		//存在則檢查狀態做判斷
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->check_state($key, $route_data, $third_id)), $route_data['private_key'], ''));
	}
	
	/*
	 * 產生密碼資料
	 * 只限於一般帳號
	 * 第三方則跳過此步驟
	 * $route_data	所需參數
	 */
	public function send_password($route_data) {
		//用md5加密
		$route_data['password'] = md5($route_data['password']);
		
		//更新密碼資料
		$this->sql->add_static(array('table'=> Table_1::$password,
									 'select'=> $this->sql->field(array(Field_1::$password, Field_1::$update_user, Field_1::$update_time), array($route_data['password'], $route_data['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$password, '', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$password, '', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '02'), $route_data['private_key'], ''));		
		} else {
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
		}
	}
	
	/*
	 * 新增其他行動會員資料或初始化
	 * $route_data 所需參數資料
	 */
	public function more_data($route_data) {
		//查詢電子郵件
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$email), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');
		//產生認證碼
		$authentication_code = $this->create->authentication();
		$email = $sql_result['email'];
		
		//查詢會員修改資料紀錄有無紀錄存在,若有則要初始化
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$action_member_alter_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'num_rows');
		//若有資料存在則初始化
		if($sql_result) {
			//初始化行動會員修改紀錄
			$this->sql->add_static(array('table'=> Table_1::$action_member_alter_log,
										 'select'=> $this->sql->field(array(Field_1::$email, Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone, Field_1::$update_user, Field_1::$update_time), array($email, $route_data['last_name'], $route_data['first_name'], $route_data['mobile_phone'], $route_data['id'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where'), array(Field_1::$id, Field_1::$frequency), array($route_data['id'], 1), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$action_member_alter_log, '加入會員_初始化行動會員修改紀錄', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$action_member_alter_log, '加入會員_初始化行動會員修改紀錄', $this->sql->get_time(1), '')),
										 'kind'=> 2));
			//初始化簡訊狀態
			$this->sql->add_static(array('table'=> Table_1::$sms_state,
										 'select'=> $this->sql->field(array(Field_3::$authentication_code, Field_3::$sms_frequency, Field_1::$update_user, Field_1::$update_time), array($authentication_code, 0, $route_data['id'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$sms_state, '加入會員_初始化簡訊狀態', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$sms_state, '加入會員_初始化簡訊狀態', $this->sql->get_time(1), '')),
										 'kind'=> 2));
			//初始化行動會員資料
			$this->sql->add_static(array('table'=> Table_1::$action_member_data,
										 'select'=> $this->sql->field(array(Field_1::$data_number, Field_1::$update_user, Field_1::$update_time), array(0, $route_data['id'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$action_member_data, '加入會員_初始化行動會員資料', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$action_member_data, '加入會員_初始化行動會員資料', $this->sql->get_time(1), '')),
										 'kind'=> 2));
		} else {
			//若沒有則新增資料
			//新增行動會員修改紀錄
			$this->sql->add_static(array('table'=> Table_1::$action_member_alter_log,
										 'select'=> $this->sql->field(array(Field_1::$frequency, Field_1::$id, Field_1::$email, Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array(1, $route_data['id'], $email, $route_data['last_name'], $route_data['first_name'], $route_data['mobile_phone'], $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))),
										 'where'=> '',
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $route_data['id'], Table_1::$action_member_alter_log, '加入會員_新增行動會員修改紀錄', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $route_data['id'], Table_1::$action_member_alter_log, '加入會員_新增行動會員修改紀錄', $this->sql->get_time(1), '')),
										 'kind'=> 1));
			//新增簡訊狀態
			$this->sql->add_static(array('table'=> Table_1::$sms_state,
										 'select'=> $this->sql->field(array(Field_1::$id, Field_3::$authentication_code, Field_3::$sms_frequency, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($route_data['id'], $authentication_code, 0, $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))),
										 'where'=> '',
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $route_data['id'], Table_1::$sms_state, '加入會員_新增簡訊狀態', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $route_data['id'], Table_1::$sms_state, '加入會員_新增簡訊狀態', $this->sql->get_time(1), '')),
										 'kind'=> 1));
			//新增行動會員資料
			$this->sql->add_static(array('table'=> Table_1::$action_member_data,
										 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$data_number, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($route_data['id'], 0, $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))),
										 'where'=> '',
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $route_data['id'], Table_1::$action_member_data, '加入會員_新增行動會員資料', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $route_data['id'], Table_1::$action_member_data, '加入會員_新增行動會員資料', $this->sql->get_time(1), '')),
										 'kind'=> 1));
		}
		
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return $authentication_code;
		} else {
			return FALSE;
		}
	}
	
	/*
	 * 更新資料及寄送認證碼簡訊
	 * $route_data	所需參數
	 */
	public function send_authentication($route_data) {
		$authentication_code = $this->more_data($route_data);
		
		//若執行成功則是認證碼
		if($authentication_code) {
			/*
			 * 這裡待加入簡訊內容規格
			 */
			$sms_form = array();
			$sms_result = $this->sms->send_sms(1, $route_data['mobile_phone'], $sms_form, $authentication_code);
			
			//判斷失敗或成功及記錄失敗原因
			if($sms_result == 1) {
				$result = 1;
				$sms_result = '';
				$code = '02';
			} else {
				$result = 2;
				$code = '03';
			}
			
			//清空之前更新存放資料
			$this->sql->clear_static();
			
			//新增簡訊紀錄
			$this->sql->add_static(array('table'=> Table_1::$sms_log,
										 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$mobile_phone, Field_2::$event, Field_2::$result, Field_2::$error_message, Field_1::$create_time), array($route_data['id'], $route_data['mobile_phone'], 1, $result, $sms_result, $this->sql->get_time(1))),
										 'where'=> '',
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $route_data['id'], Table_1::$sms_log, '', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $route_data['id'], Table_1::$sms_log, '', $this->sql->get_time(1), '')),
										 'kind'=> 1));
			//執行
			$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind));
				
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . $code), $route_data['private_key'], ''));
		} else {
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
		}
	}
	
	
	
	
	
	
	
	
}