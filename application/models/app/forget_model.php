<?php

class Forget_model extends CI_Model {
	/*
	 * 忘記密碼起點函式
	 * $route_data從APP來的參數
	 */
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '2_1':
				return $this->create_key($route_data);
				break;
			case '2_2':
				return $this->send_password($route_data);
				break;
		}
	}
	
	/*
	 * 產生金鑰組並回傳加密公鑰
	 * 因為怕被駭客攔截
	 * 所以使用APP產生的公鑰去加密要給APP的公鑰
	 * $route_data 所需參數
	 */
	public function create_key($route_data) {
		$app = '2_1';
		$key = $this->key->create_key();				
		
		//用意是要符合json格式
		$public_key['public_key'] = $key['public_key'];				
		$outer_array = array();						
		array_push($outer_array, $public_key);		

		//查詢手機ID是否存在,若存在則更新金鑰組,不存在則新增一個手機ID及金鑰組
		$sql_select = $this->sql->select(array('mobile_phone_id'), '');
		$sql_where = $this->sql->where(array('where'), array('mobile_phone_id'), array($route_data['mobile_phone_id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'moblie_phone_id_and_key', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'num_rows');
		
		if($sql_result) {
			//更新金鑰組
			array_push(Sql::$table, 'moblie_phone_id_and_key');
			array_push(Sql::$select, $this->sql->field(array('private_key', 'public_key', 'update_time'), array($key['private_key'], $key['public_key'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('mobile_phone_id'), array($route_data['mobile_phone_id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, 1, 'moblie_phone_id_and_key', '該手機ID存在,更新金鑰組', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, 1, 'moblie_phone_id_and_key', '該手機ID存在,更新金鑰組', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);	
		} else {
			//新增一個手機ID及金鑰組
			array_push(Sql::$table, 'moblie_phone_id_and_key');
			array_push(Sql::$select, $this->sql->field(array('mobile_phone_id', 'private_key', 'public_key', 'create_time', 'update_time'), array($route_data['mobile_phone_id'], $key['private_key'], $key['public_key'], $this->sql->get_time(1), $this->sql->get_time(1))));
			array_push(Sql::$where, '');
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, 1, 'moblie_phone_id_and_key', '該手機ID不存在,新增該手機ID及金鑰組', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, 1, 'moblie_phone_id_and_key', '該手機ID不存在,新增該手機ID及金鑰組', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 1);
		}
		
		//執行新增/更新
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind) === FALSE) {
			$json_data = $this->json->encode_json($app, '2_101');
		} else {
			$json_data = $this->json->encode_json($app, $outer_array);
		}

		$encode_data = $this->key->encode_app_public($json_data, $route_data['public_key']);
		return $this->json->encode_json(1, $encode_data);
	}
	
	/*
	 * 寄送密碼給該會員電子信箱
	 * $route_data	所需參數資料
	 */
	public function send_password($route_data) {
		$app = '2_2';
		
		//查詢會員編號
		$sql_select = $this->sql->select(array('id'), '');
		$sql_where = $this->sql->where(array('where'), array('email'), array($route_data['email']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$id = $sql_result['id'];
		
		if($id = '') {
			$json_data = $this->json->encode_json($app, '2_201');
		} else {
			//查詢該會員密碼
			$sql_select = $this->sql->select(array('password'), '');
			$sql_where = $this->sql->where(array('where'), array('id'), array($id), array(''));
			$sql_query = $this->query_model->query($sql_select, 'password', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'row_array');
			$password = $sql_result['password'];
			
			//查詢忘記密碼目前使用電子郵件版本
			$sql_select = $this->sql->select(array('server_name', 'server_port', 'account', 'password', 'send_email', 'send_name', 'subject', 'body'), '');
			$sql_where = $this->sql->where(array('where', 'where'), array('form_kind', 'state'), array(1, 'y'), array(''));
			$sql_query = $this->query_model->query($sql_select, 'email_form', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'row_array');
			
			//寄發電子郵件
			$send_result = $this->email->send_email(1, $route_data['email'], $sql_result, $password);
			
			if($send_result == 1) {
				$send_result = '';
				$result = 1;
				$json_data = $this->json->encode_json($app, '2_202');
			} else {
				$result = 2;
				$json_data = $this->json->encode_json($app, '2_203');
			}
			
			//新增電子郵件紀錄
			array_push(Sql::$table, 'email_log');
			array_push(Sql::$select, $this->sql->field(array('id', 'email', 'event', 'result', 'file_name', 'error_message', 'create_time'), array($id, $route_data['email'], 1, $result, '', $send_result, $this->sql->get_time(1))));
			array_push(Sql::$where, '');
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $id, 'email_log', '忘記密碼電子郵件紀錄', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $id, 'email_log', '忘記密碼電子郵件紀錄', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 1);
			
			//執行更新
			if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind) === FALSE) {
				$json_data = $this->json->encode_json($app, '2_204');
			}
		}

		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json(1, $encode_data);
	}
}