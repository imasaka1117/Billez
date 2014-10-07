<?php

class Forget_model extends CI_Model {
	/*
	 * 忘記密碼起點函式
	 * $route_data從APP來的參數
	 */
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '2_2':
				return $this->send_password($route_data);
				break;
		}
	}

	/*
	 * 寄送密碼給該會員電子信箱
	 * $route_data	所需參數資料
	 */
	public function send_password($route_data) {
		//查詢該會員是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$email), array($route_data['email']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) {
			//查詢該會員編號
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$email), array($route_data['email']), array('')),
																		 'other' => '')), 'row_array');
			$route_data['id'] = $sql_result['id'];
			$route_data['password'] = $this->create->authentication();
			
			//更新新的密碼
			$this->sql->add_static(array('table'=> Table_1::$password,
										 'select'=> $this->sql->field(array(Field_1::$password, Field_1::$update_user, Field_1::$update_time), array(md5($route_data['password']), $route_data['id'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$password, '忘記密碼_更新新的密碼', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$password, '忘記密碼_更新新的密碼', $this->sql->get_time(1), '')),
										 'kind'=> 2));
			//執行
			if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
				//查詢忘記密碼目前使用的電子郵件版本
				$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$server_name, Field_2::$server_port, Field_2::$account, Field_1::$password, Field_2::$send_email, Field_2::$send_name, Field_2::$subject, Field_2::$body), ''),
																				 'from' => Table_1::$email_form,
																				 'join'=> '',
																				 'where' => $this->sql->where(array('where'), array(Field_2::$form_kind, Field_1::$state), array(1, 'y'), array('')),
																				 'other' => '')), 'row_array');
				//回傳傳送結果
				return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->email_model->send_email($route_data, $sql_result, array('record' => '忘記密碼_新增寄發電子郵件紀錄', 'event' => 1, 'success' => '02', 'fail' => '03'))), $route_data['private_key'], ''));
			} else {
				//回傳查無此帳號
				return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '04'), $route_data['private_key'], ''));
			}
		} else {
			//回傳查無此帳號
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
		}
	}
}// class end