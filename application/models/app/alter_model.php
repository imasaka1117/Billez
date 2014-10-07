<?php

class Alter_model extends CI_Model {
	/*
	 * 修改會員資料起點函式
	 * $route_data從APP來的參數
	 */
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '3_1':
				return $this->check_mobile_phone($route_data);
				break;
			case '3_2':
				return $this->sms_model->send_again($route_data, array('event' => 4, 'success' => '02', 'fail' => '03'));
				break;
			case '3_3':
				return $this->check_authentication($route_data);
				break;
		}
	}
	
	/*
	 * 修改資料處理
	 * $route_data	所需參數資料
	 * $action_member_info	目前的會員基本資料
	 * $code 回傳的認證碼
	 */
	public function alter_data($route_data, $action_member_info, $code) {
		//查詢最大修改次數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$frequency . ') AS max'), 'function'),
																		 'from' => Table_1::$action_member_alter_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');
		$frequency = $sql_result['max'] + 1;

		//新增修改記錄資料,把目前的資料移到修改記錄
		$this->sql->add_static(array('table'=> Table_1::$action_member_alter_log,
									 'select'=> $this->sql->field(array(Field_1::$frequency, Field_1::$id, Field_1::$email, Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($frequency, $route_data['id'], $action_member_info['email'], $action_member_info['last_name'], $action_member_info['first_name'], $action_member_info['mobile_phone'], $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $route_data['id'], Table_1::$action_member_alter_log, '修改會員資料_新增會員修改紀錄', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $route_data['id'], Table_1::$action_member_alter_log, '修改會員資料_新增會員修改紀錄', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//更新行動會員基本資料
		$this->sql->add_static(array('table'=> Table_1::$action_member,
									 'select'=> $this->sql->field(array(Field_1::$email, Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone, Field_1::$update_user, Field_1::$update_time), array($route_data['new_email'], $route_data['last_name'], $route_data['first_name'], $route_data['mobile_phone'], $route_data['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$action_member, '修改會員資料_更新會員基本資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$action_member, '修改會員資料_更新會員基本資料', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//更新會員密碼
		$this->sql->add_static(array('table'=> Table_1::$password,
									 'select'=> $this->sql->field(array(Field_1::$password, Field_1::$temp_password, Field_1::$update_user, Field_1::$update_time), array($route_data['password'], '', $route_data['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$password, '修改會員資料_更新會員密碼', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$password, '修改會員資料_更新會員密碼', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//清空簡訊認證碼及次數
		$this->sql->add_static(array('table'=> Table_1::$sms_state,
									 'select'=> $this->sql->field(array(Field_3::$sms_frequency, Field_3::$authentication_code, Field_3::$authentication_code2, Field_3::$authentication_code3, Field_1::$update_user, Field_1::$update_time), array(0, '', '', '', $route_data['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$sms_state, '修改會員資料_初始化認證碼和傳送次數', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$sms_state, '修改會員資料_初始化認證碼和傳送次數', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			//更新成功
			return $route_data['sub_param'] . $code['success'];
		} else {
			//更新失敗
			return $route_data['sub_param'] . $code['fail'];
		}
	}
	
	/*
	 * 修改資料
	 * 若是有改變手機號碼
	 * 則要使用簡訊認證碼認證
	 * 若是沒有改變手機號碼
	 * 則直接更改
	 * $route_data 所需參數資料
	 */
	public function check_mobile_phone($route_data) {
		//查詢會員基本資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$email, Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');
		$action_member_info = $sql_result;

		//查詢要更改的電子郵件是否已經有人使用
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$email), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id . ' !=', Field_1::$email), array($route_data['id'], $route_data['new_email']), array('')),
																		 'other' => '')), 'num_rows');
		//若存在則代表已經有人申請該更換電子郵件
		if($sql_result) return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
		
		//將密碼加密
		$route_data['password'] = md5($route_data['password']);
		
		//若是手機號碼沒改,就直接更新資料
		if($route_data['mobile_phone'] == $action_member_info['mobile_phone']) return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->alter_data($route_data, $action_member_info, array('success' => '05', 'fail' => '06'))), $route_data['private_key'], ''));
		
		//將每一筆的修改資料紀錄的第一筆作為暫存資料,待驗證後使用這筆資料轉移到基本資料
		$authentication_code = $this->create->authentication();
		
		//更新第一筆資料內容
		$this->sql->add_static(array('table'=> Table_1::$action_member_alter_log,
									 'select'=> $this->sql->field(array(Field_1::$email, Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone, Field_1::$update_user, Field_1::$update_time), array($route_data['new_email'], $route_data['last_name'], $route_data['first_name'], $route_data['mobile_phone'], $route_data['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where', 'where'), array(Field_1::$id, Field_1::$frequency), array($route_data['id'], 1), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$action_member_alter_log, '修改會員資料_更新第一筆修改紀錄內容', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$action_member_alter_log, '修改會員資料_更新第一筆修改紀錄內容', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//更新簡訊狀態,認證碼
		$this->sql->add_static(array('table'=> Table_1::$sms_state,
									 'select'=> $this->sql->field(array(Field_3::$authentication_code, Field_3::$sms_frequency, Field_1::$update_user, Field_1::$update_time), array($authentication_code, 0, $route_data['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$sms_state, '修改會員資料_更新簡訊認證碼狀態', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$sms_state, '修改會員資料_更新簡訊認證碼狀態', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//更新暫存密碼
		$this->sql->add_static(array('table'=> Table_1::$password,
									 'select'=> $this->sql->field(array(Field_1::$temp_password, Field_1::$update_user, Field_1::$update_time), array($route_data['password'], $route_data['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$password, '修改會員資料_更新密碼放置暫存密碼', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$password, '修改會員資料_更新密碼放置暫存密碼', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			//若是更新成功則寄發認證碼簡訊
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->sms_model->send_sms($route_data, $authentication_code, array('event' => 3, 'success' => '02', 'fail' => '03'))), $route_data['private_key'], ''));
		} else {
			//失敗回傳狀態碼
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '04'), $route_data['private_key'], ''));
		}
	}
	
	/*
	 * 確認認證碼
	 * 更改資料
	 * $route_data 所需參數資料
	 */
	public function check_authentication($route_data) {
		//檢查認證碼是否正確
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$sms_state,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'or_where', 'or_where', 'where'), array(Field_3::$authentication_code, Field_3::$authentication_code2, Field_3::$authentication_code3, Field_1::$id), array($route_data['authentication_code'], $route_data['authentication_code'], $route_data['authentication_code'], $route_data['id']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) {
			//正確則更新資料
			//查詢暫存密碼
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$temp_password), ''),
																			 'from' => Table_1::$password,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																			 'other' => '')), 'row_array');
			$route_data['password'] = $sql_result['temp_password'];
			
			//查詢暫存的更新會員資料
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$email, Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone), ''),
																			 'from' => Table_1::$action_member_alter_log,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_1::$id, Field_1::$frequency), array($route_data['id'], 1), array('')),
																			 'other' => '')), 'row_array');
			$route_data['new_email'] = $sql_result['email'];
			$route_data['last_name'] = $sql_result['last_name'];
			$route_data['first_name'] = $sql_result['first_name'];
			$route_data['mobile_phone'] = $sql_result['mobile_phone'];
		
			//查詢會員基本資料
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$email, Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone), ''),
																			 'from' => Table_1::$action_member,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																			 'other' => '')), 'row_array');
			$action_member_info = $sql_result;
			
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->alter_data($route_data, $action_member_info, array('success' => '02', 'fail' => '03'))), $route_data['private_key'], '')); 
		} else {
			//不正確回傳錯誤碼
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
		}
	}	
}