<?php

class Alter_model extends CI_Model {
	/*
	 * 修改會員資料起點函式
	* $route_data從APP來的參數
	*/
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '3_1':
				return $this->alter_data($route_data);
				break;
			case '3_2':
				return $this->send_again($route_data);
				break;
			case '3_3':
				return $this->check_authentication($route_data);
				break;
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
	public function alter_data($route_data) {
		$app = '3_1';
		
		//查詢會員電子郵件
		$sql_select = $this->sql->select(array('email'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$email = $sql_result['email'];
		
		//查詢會員基本資料
		$sql_select = $this->sql->select(array('email', 'last_name', 'first_name', 'mobile_phone', 'state'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$now_action_member_info = $sql_result;
		
		//查詢會員密碼
		$sql_select = $this->sql->select(array('password'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'password', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$now_password = $sql_result['password'];
		
		//查詢要更改的電子郵件是否已經有人使用
		$sql_select = $this->sql->select(array('email'), '');
		$sql_where = $this->sql->where(array('where', 'where'), array('id !=', 'email'), array($route_data['id'], $route_data['new_email']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'num_rows');

		if($sql_result) {
			$json_data = $this->json->encode_json($app, '3_101');
			echo $json_data;exit();
			$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
			return $this->json->encode_json('vale', $encode_data);
		}
		
		//若是手機號碼沒改,就直接更新資料
		if($route_data['mobile_phone'] == $now_action_member_info['mobile_phone']) {
			//查詢最大修改次數
			$sql_select = $this->sql->select(array('frequency'), 'max');
			$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
			$sql_query = $this->query_model->query($sql_select, 'action_member_alter_log', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'row_array');
			$frequency = $sql_result['max'] + 1;
			
			//新增修改記錄資料,把目前的資料移到修改記錄
			array_push(Sql::$table, 'action_member_alter_log');
			array_push(Sql::$select, $this->sql->field(array('frequency', 'id', 'email', 'password', 'last_name', 'first_name', 'mobile_phone', 'create_user', 'create_time', 'update_user', 'update_time'), array($frequency, $route_data['id'], $now_action_member_info['email'], $now_password, $now_action_member_info['last_name'], $now_action_member_info['first_name'], $now_action_member_info['mobile_phone'], $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, '');
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'action_member_alter_log', '修改資料新增修改記錄', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'action_member_alter_log', '修改資料新增修改記錄', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 1);
			
			//更新行動會員基本資料
			array_push(Sql::$table, 'action_member');
			array_push(Sql::$select, $this->sql->field(array('email', 'last_name', 'first_name', 'mobile_phone', 'update_user', 'update_time'), array($route_data['new_email'], $route_data['last_name'], $route_data['first_name'], $route_data['mobile_phone'], $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'action_member', '修改資料更新會員基本資料', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'action_member', '修改資料更新會員基本資料', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
			
			//更新會員密碼
			array_push(Sql::$table, 'password');
			array_push(Sql::$select, $this->sql->field(array('password', 'update_user', 'update_time'), array($route_data['password'], $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'password', '修改資料更新會員密碼', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'password', '修改資料更新會員密碼', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
			
			//執行更新
			if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
				$json_data = $this->json->encode_json($app, '3_102');
			} else {
				$json_data = $this->json->encode_json($app, '3_103');
			}
			echo $json_data;exit();
			$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
			return $this->json->encode_json('vale', $encode_data);
		}
		
		//將每一筆的修改資料紀錄的第一筆作為暫存資料,待驗證後使用這筆資料轉移到基本資料
		$frequency = 1;
		$authentication_code = $this->create->authentication();
		
		//更新第一筆資料內容
		array_push(Sql::$table, 'action_member_alter_log');
		array_push(Sql::$select, $this->sql->field(array('email', 'password', 'last_name', 'first_name', 'mobile_phone', 'update_user', 'update_time'), array($route_data['new_email'], $route_data['password'], $route_data['last_name'], $route_data['first_name'], $route_data['mobile_phone'], $route_data['id'], $this->sql->get_time(1))));
		array_push(Sql::$where, $this->sql->where(array('where', 'where'), array('id', 'frequency'), array($route_data['id'], $frequency), array('')));
		array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'action_member_alter_log', '修改資料更新第一筆修改紀錄', $this->sql->get_time(1))));
		array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'action_member_alter_log', '修改資料更新第一筆修改紀錄', $this->sql->get_time(1), '')));
		array_push(Sql::$kind, 2);
		
		//更新簡訊狀態,認證碼
		array_push(Sql::$table, 'sms_state');
		array_push(Sql::$select, $this->sql->field(array('authentication_code', 'sms_frequency', 'update_user', 'update_time'), array($authentication_code, 0, $route_data['id'], $this->sql->get_time(1))));
		array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
		array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'sms_state', '修改資料更新簡訊狀態認證碼', $this->sql->get_time(1))));
		array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'sms_state', '修改資料更新簡訊狀態認證碼', $this->sql->get_time(1), '')));
		array_push(Sql::$kind, 2);
		
		//執行更新並寄送簡訊
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
			/*
			 * 這裡待加入簡訊內容規格
			*/
					
			$sms_result = $this->sms->send_sms(1, $route_data['mobile_phone'], '', $authentication_code);
			
			if($sms_result == 1) {
				$sms_result = '';
				$result = 1;
				$json_data = $this->json->encode_json($app, '3_104');
			} else {
				$result = 2;
				$json_data = $this->json->encode_json($app, '3_105');
			}
			
			$this->sql->clear_static();
			
			//新增簡訊記錄
			array_push(Sql::$table, 'sms_log');
			array_push(Sql::$select, $this->sql->field(array('id', 'mobile_phone', 'event', 'result', 'error_message', 'create_time'), array($route_data['id'], $route_data['mobile_phone'], 3, $result, $sms_result, $this->sql->get_time(1))));
			array_push(Sql::$where, '');
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'sms_log', '加入會員再次寄發簡訊認證碼紀錄', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'sms_log', '加入會員再次寄發簡訊認證碼紀錄', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 1);
			
			$this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind);
		} else {
			$json_data = $this->json->encode_json($app, '3_106');
		}
		echo $json_data;exit();
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
	
	/*
	 * 再次寄送簡訊認證碼
	 * 總共有三個認證碼都可通過
	 * $route_data 所需資料
	 */
	public function send_again($route_data) {
		$app = '3_2';
		
		//查詢暫存的新修改手機
		$sql_select = $this->sql->select(array('mobile_phone'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member_alter_log', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$mobile_phone = $sql_result['mobile_phone'];
		
		//查詢會員簡訊狀態
		$sql_select = $this->sql->select(array('sms_frequency', 'authentication_code'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'sms_state', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$sms_state_info = $sql_result;
		
		//查詢簡訊傳送次數上限
		$sql_select = $this->sql->select(array('sms_times'), '');
		$sql_where = $this->sql->where(array('where'), array('using'), array('y'), array(''));
		$sql_query = $this->query_model->query($sql_select, 'system_setting', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$sms_times_limit = $sql_result['sms_times'];
		
		//產生認證碼
		$authentication_code = $this->create->authentication();
		
		//若已傳送次數等於系統設定就不再傳送
		if($sms_state_info['sms_frequency'] == $sms_times_limit) {				
			$json_data = $this->json->encode_json($app, '3_201');

			$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
			return $this->json->encode_json('vale', $encode_data);
		}
		
		//更新簡訊次數
		array_push(Sql::$table, 'sms_state');
		array_push(Sql::$select, $this->sql->field(array('sms_frequency', 'update_user', 'update_time'), array(++$sms_state_info['sms_frequency'], $route_data['id'], $this->sql->get_time(1))));
		array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
		array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'sms_state', '修改會員增加傳送簡訊次數', $this->sql->get_time(1))));
		array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'sms_state', '修改會員增加傳送簡訊次數', $this->sql->get_time(1), '')));
		array_push(Sql::$kind, 2);
		
		//查詢認證碼2是否產生
		$sql_select = $this->sql->select(array('authentication_code2'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'sms_state', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		
		if($sql_result['authentication_code2'] == '') {
			//更新認證碼2
			array_push(Sql::$table, 'sms_state');
			array_push(Sql::$select, $this->sql->field(array('authentication_code2', 'update_user', 'update_time'), array($authentication_code, $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'sms_state', '修改會員增加認證碼2', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'sms_state', '修改會員增加認證碼2', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
		} else {
			//更新認證碼3
			array_push(Sql::$table, 'sms_state');
			array_push(Sql::$select, $this->sql->field(array('authentication_code3', 'update_user', 'update_time'), array($authentication_code, $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'sms_state', '修改會員增加認證碼3', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'sms_state', '修改會員增加認證碼3', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
		}
		
		//執行更新,寄發認證碼簡訊
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
			/*
			 * 這裡待加入簡訊內容規格
			 */

			$sms_result = $this->sms->send_sms(1, $mobile_phone, '', $authentication_code);
				
			if($sms_result == 1) {
				$result = 1;
				$sms_result = '';
				$json_data = $this->json->encode_json($app, '3_202');
			} else {
				$result = 2;
				$json_data = $this->json->encode_json($app, '3_203');
			}
			
			$this->sql->clear_static();
			//新增簡訊記錄
			array_push(Sql::$table, 'sms_log');
			array_push(Sql::$select, $this->sql->field(array('id', 'mobile_phone', 'event', 'result', 'error_message', 'create_time'), array($route_data['id'], $mobile_phone, 4, $result, $sms_result, $this->sql->get_time(1))));
			array_push(Sql::$where, '');
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'sms_log', '修改會員再次寄發簡訊認證碼紀錄', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'sms_log', '修改會員再次寄發簡訊認證碼紀錄', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 1);
				
			$this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind);
		}
		
		$json_data = $this->json->encode_json($app, '3_204');
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
	
	/*
	 * 確認認證碼
	 * 更改資料
	 * $route_data 所需參數資料
	 */
	public function check_authentication($route_data) {
		$app = '3_3';
		
		//查詢目前密碼
		$sql_select = $this->sql->select(array('password'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'password', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$now_password = $sql_result['password'];
		
		//查詢認證碼是否正確
		$sql_select = $this->sql->select(array('id'), '');
		$sql_where = $this->sql->where(array('where', 'or_where', 'or_where', 'where'), array('authentication_code', 'authentication_code2', 'authentication_code3', 'id'), array($route_data['authentication_code'], $route_data['authentication_code'], $route_data['authentication_code'], $route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'sms_state', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'num_rows');
		
		if($sql_result) {
			//查詢暫存的第一筆資料
			$sql_select = $this->sql->select(array('email', 'password', 'last_name', 'first_name', 'mobile_phone'), '');
			$sql_where = $this->sql->where(array('where'), array('id', 'frequency'), array($route_data['id'], 1), array(''));
			$sql_query = $this->query_model->query($sql_select, 'action_member_alter_log', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'row_array');
			$new_action_member_info = $sql_result;
			
			//查詢最大修改次數
			$sql_select = $this->sql->select(array('frequency'), 'max');
			$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
			$sql_query = $this->query_model->query($sql_select, 'action_member_alter_log', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'row_array');
			$frequency = $sql_result['max'] + 1;
			
			//查詢會員基本資料
			$sql_select = $this->sql->select(array('email', 'last_name', 'first_name', 'mobile_phone', 'state'), '');
			$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
			$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'row_array');
			$now_action_member_info = $sql_result;
			
			//新增修改記錄資料,把目前的資料移到修改記錄
			array_push(Sql::$table, 'action_member_alter_log');
			array_push(Sql::$select, $this->sql->field(array('frequency', 'id', 'email', 'password', 'last_name', 'first_name', 'mobile_phone', 'create_user', 'create_time', 'update_user', 'update_time'), array($frequency, $route_data['id'], $now_action_member_info['email'], $now_password, $now_action_member_info['last_name'], $now_action_member_info['first_name'], $now_action_member_info['mobile_phone'], $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, '');
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'action_member_alter_log', '修改資料新增修改記錄', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'action_member_alter_log', '修改資料新增修改記錄', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 1);
				
			//更新行動會員基本資料
			array_push(Sql::$table, 'action_member');
			array_push(Sql::$select, $this->sql->field(array('email', 'last_name', 'first_name', 'mobile_phone', 'update_user', 'update_time'), array($new_action_member_info['email'], $new_action_member_info['last_name'], $new_action_member_info['first_name'], $new_action_member_info['mobile_phone'], $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'action_member', '修改資料更新會員基本資料', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'action_member', '修改資料更新會員基本資料', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
				
			//更新會員密碼
			array_push(Sql::$table, 'password');
			array_push(Sql::$select, $this->sql->field(array('password', 'update_user', 'update_time'), array($new_action_member_info['password'], $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'password', '修改資料更新會員密碼', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'password', '修改資料更新會員密碼', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
			
			//執行更新
			if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
				$json_data = $this->json->encode_json($app, '3_301');
			} else {
				$json_data = $this->json->encode_json($app, '3_302');
			}
		} else {
			$json_data = $this->json->encode_json($app, '3_303');
		}
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}	
}