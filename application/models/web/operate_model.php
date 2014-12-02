<?php

class Operate_model extends CI_Model {
	/*
	 * 查詢操作紀錄資料
	 * $post 查詢條件資料
	 */
	public function search_operate($post) {
		if(strlen($post['operator']) > 1) $post['operator'] = '';
		if(strlen($post['user']) > 9) $post['user'] = '';
		if(strlen($post['table']) > 40) $post['table'] = '';
		if(strlen($post['time']) > 10) $post['time'] = '';
		
		//查詢操作紀錄列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$user), ''),
																		 'from' => Table_1::$user_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$create_time), array($post['operator'], $post['user'], $post['table'], $post['time'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
	
		//查詢操作紀錄
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), ''),
																		 'from' => Table_1::$user_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$create_time), array($post['operator'], $post['user'], $post['table'], $post['time'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		$system_data = array();
		foreach($sql_result as $data) {
			$data['operate'] = $this->transform->system_operate($data['operate']);
			$data['user'] = $this->transform->system_user($data['user']);
			array_push($system_data, $data);
		}
	
		return $this->option->table($system_data, array('操作', '操作者', '資料表', '操作目的', '時間'), base_url() . Param::$index_url . 'error/update_sms_web') . $this->option->page($page_count, $post['page']);
	}
	
	/*
	 * 新增電子郵件設定
	 * $post 新增資料
	 * $user 使用者
	 */
	public function insert_email_set($post, $user) {
		//檢查名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$form_name), ''),
																		 'from' => Table_1::$email_form,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_2::$form_name), array($post['form_name']), array('')),
																		 'other' => '')), 'num_rows');
		//若存在則回傳錯誤訊息
		if($sql_result) return 1;
		
		//新增電子郵件設定
		$this->sql->add_static(array('table'=> Table_1::$email_form,
									 'select'=> $this->sql->field(array(Field_2::$form_name, Field_2::$form_kind, Field_1::$state, Field_2::$server_name, Field_2::$server_port, Field_2::$account, Field_1::$password, Field_2::$send_email, Field_2::$send_name, Field_2::$subject, Field_2::$body, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($post['form_name'], $post['form_kind'], 'n', $post['server_name'], $post['server_port'], $post['account'], $post['password'], $post['send_email'], $post['send_name'], $post['subject'], $post['body'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$email_form, '新增電子郵件設定_新增電子郵件設定', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$email_form, '新增電子郵件設定_新增電子郵件設定', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'reload';
		} else {
			return 2;
		}
	}
	
	/*
	 * 查詢電子郵件設定資料
	 * $post 資料
	 */
	public function search_email_set_data($post) {
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$form_name, Field_2::$form_kind, Field_1::$state, Field_2::$server_name, Field_2::$server_port, Field_2::$account, Field_1::$password, Field_2::$send_email, Field_2::$send_name,Field_2::$subject, Field_2::$body), ''),
																		 'from' => Table_1::$email_form,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_2::$form_name), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 更新電子郵件設定
	 * $post 更新資料
	 * $user 使用者
	 */
	public function update_email_set($post, $user) {
		if($post['state'] == 'y') {
			//先將該種類啟用更改為不啟用
			$this->sql->add_static(array('table'=> Table_1::$email_form,
										 'select'=> $this->sql->field(array(Field_1::$state), array('n')),
										 'where'=> $this->sql->where(array('where'), array(Field_2::$form_kind), array($post['form_kind']), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$email_form, '查詢電子郵件設定_更改種類設定為不啟用', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$email_form, '查詢電子郵件設定_更改種類設定為不啟用', $this->sql->get_time(1), '')),
										 'kind'=> 2));
			//執行
			if(!$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
				return 1;
			}
		}
		
		//更新電子郵件設定
		$this->sql->add_static(array('table'=> Table_1::$email_form,
									 'select'=> $this->sql->field(array(Field_2::$form_kind, Field_1::$state, Field_2::$server_name, Field_2::$server_port, Field_2::$account, Field_1::$password, Field_2::$send_email, Field_2::$send_name, Field_2::$subject, Field_2::$body, Field_1::$update_user, Field_1::$update_time), array($post['form_kind'],$post['state'], $post['server_name'], $post['server_port'], $post['account'], $post['password'], $post['send_email'], $post['send_name'], $post['subject'], $post['body'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_2::$form_name), array($post['form_name']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$email_form, '查詢電子郵件設定_更新電子郵件設定', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$email_form, '查詢電子郵件設定_更新電子郵件設定', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'reload';
		} else {
			return 1;
		}
	}
	
	/*
	 * 新增簡訊設定
	* $post 新增資料
	* $user 使用者
	*/
	public function insert_sms_set($post, $user) {
		//檢查名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$form_name), ''),
																		 'from' => Table_1::$sms_form,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_2::$form_name), array($post['form_name']), array('')),
																		 'other' => '')), 'num_rows');
		//若存在則回傳錯誤訊息
		if($sql_result) return 1;
	
		//新增簡訊設定
		$this->sql->add_static(array('table'=> Table_1::$sms_form,
									 'select'=> $this->sql->field(array(Field_2::$form_name, Field_2::$form_kind, Field_1::$state, Field_2::$server_name, Field_2::$server_port, Field_2::$account, Field_1::$password, Field_2::$body, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($post['form_name'], $post['form_kind'], 'n', $post['server_name'], $post['server_port'], $post['account'], $post['password'], $post['body'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$sms_form, '新增簡訊設定_新增簡訊設定', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$sms_form, '新增簡訊設定_新增簡訊設定', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'reload';
		} else {
			return 2;
		}
	}
	
	/*
	 * 查詢簡訊設定資料
	* $post 資料
	*/
	public function search_sms_set_data($post) {
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$form_name, Field_2::$form_kind, Field_1::$state, Field_2::$server_name, Field_2::$server_port, Field_2::$account, Field_1::$password, Field_2::$body), ''),
																		 'from' => Table_1::$sms_form,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_2::$form_name), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 更新簡訊設定
	* $post 更新資料
	* $user 使用者
	*/
	public function update_sms_set($post, $user) {
		if($post['state'] == 'y') {
			//先將該種類啟用更改為不啟用
			$this->sql->add_static(array('table'=> Table_1::$sms_form,
										 'select'=> $this->sql->field(array(Field_1::$state), array('n')),
										 'where'=> $this->sql->where(array('where'), array(Field_2::$form_kind), array($post['form_kind']), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$sms_form, '查詢簡訊設定_更改種類設定為不啟用', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$sms_form, '查詢簡訊設定_更改種類設定為不啟用', $this->sql->get_time(1), '')),
										 'kind'=> 2));
			//執行
			if(!$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
				return 1;
			}
		}
	
		//更新簡訊設定
		$this->sql->add_static(array('table'=> Table_1::$sms_form,
									 'select'=> $this->sql->field(array(Field_2::$form_kind, Field_1::$state, Field_2::$server_name, Field_2::$server_port, Field_2::$account, Field_1::$password, Field_2::$body, Field_1::$update_user, Field_1::$update_time), array($post['form_kind'],$post['state'], $post['server_name'], $post['server_port'], $post['account'], $post['password'], $post['body'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_2::$form_name), array($post['form_name']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$sms_form, '查詢簡訊設定_更新簡訊設定', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$sms_form, '查詢簡訊設定_更新簡訊設定', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'reload';
		} else {
			return 1;
		}
	}
	
	/*
	 * 新增系統設定
	 * $post 新增資料
	 * $user 使用者
	 */
	public function insert_system_set($post, $user) {
		//檢查名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$name), ''),
																		 'from' => Table_1::$system_set,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$name), array($post['name']), array('')),
																		 'other' => '')), 'num_rows');
		//若存在則回傳錯誤訊息
		if($sql_result) return 1;
		
		//新增系統設定
		$this->sql->add_static(array('table'=> Table_1::$system_set,
									 'select'=> $this->sql->field(array(Field_1::$name, Field_3::$using, Field_3::$push_times, Field_3::$sms_times, Field_4::$get_file_time, Field_4::$possible_bill_time, Field_4::$error_list_time, Field_4::$repeat_push_time, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($post['name'], 'n', $post['push_times'], $post['sms_times'], $post['get_file_time'], $post['possible_bill_time'], $post['error_list_time'], $post['repeat_push_time'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$system_set, '新增系統設定_新增電子郵件設定', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$system_set, '新增系統設定_新增電子郵件設定', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'reload';
		} else {
			return 2;
		}
	}
	
	/*
	 * 查詢系統設定資料
	 * $post 資料
	 */
	public function search_system_set_data($post) {
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$name, Field_3::$using, Field_3::$push_times, Field_3::$sms_times, Field_4::$get_file_time, Field_4::$possible_bill_time, Field_4::$error_list_time, Field_4::$repeat_push_time), ''),
																		 'from' => Table_1::$system_set,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$name), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 更新系統設定
	 * $post 更新資料
	 * $user 使用者
	 */
	public function update_system_set($post, $user) {
		if($post['using'] == 'y') {
			//先將啟用更改為不啟用
			$this->sql->add_static(array('table'=> Table_1::$system_set,
										 'select'=> $this->sql->field(array(Field_3::$using), array('n')),
										 'where'=> '',
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$system_set, '查詢系統設定_更改啟用設定為不啟用', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$system_set, '查詢系統設定_更改啟用設定為不啟用', $this->sql->get_time(1), '')),
										 'kind'=> 2));
			//執行
			if(!$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
				return 1;
			}
		}
	
		//更新系統設定
		$this->sql->add_static(array('table'=> Table_1::$system_set,
				'select'=> $this->sql->field(array(Field_3::$using, Field_3::$push_times, Field_3::$sms_times, Field_4::$get_file_time, Field_4::$possible_bill_time, Field_4::$error_list_time, Field_4::$repeat_push_time, Field_1::$update_user, Field_1::$update_time), array($post['using'], $post['push_times'], $post['sms_times'], $post['get_file_time'], $post['possible_bill_time'], $post['error_list_time'], $post['repeat_push_time'], $user['id'], $this->sql->get_time(1))),
				'where'=> $this->sql->where(array('where'), array(Field_1::$name), array($post['name']), array('')),
				'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$system_set, '查詢系統設定_更新系統設定', $this->sql->get_time(1))),
				'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$system_set, '查詢系統設定_更新系統設定', $this->sql->get_time(1), '')),
				'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'reload';
		} else {
			return 1;
		}
	}
	
	/*
	 * 更改排程設定
	 * $post 更新資料
	 * $user 使用者
	 */
	public function change_scheduling($post, $user) {
		//檢查排程狀態
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$state, Field_5::$day), ''),
																		 'from' => Table_1::$scheduling_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$name), array($post['kind']), array('')),
																		 'other' => '')), 'row_array');
		//若是處於暫停狀態,則將暫停天數清空為null
		if(isset($sql_result['day'])) {
			if($post['state'] == $sql_result['state']) {
				$this->sql->add_static(array('table'=> Table_1::$scheduling_log,
											 'select'=> $this->sql->field(array(Field_5::$day), array(null)),
											 'where'=> $this->sql->where(array('where'), array(Field_1::$name), array($post['kind']), array('')),
											 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$scheduling_log, '排程設定_清除暫停天數', $this->sql->get_time(1))),
											 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$scheduling_log, '排程設定_清除暫停天數', $this->sql->get_time(1), '')),
											 'kind'=> 2));
				//執行
				if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
					sleep(11);
					return 4;
				} else {
					return 5;
				}
			}
		}

		//相同狀態
		if($post['state'] == $sql_result['state']) return 1;
		
		if($sql_result['state'] == 'y') {
			//關閉該排程
			$this->sql->add_static(array('table'=> Table_1::$scheduling_log,
										 'select'=> $this->sql->field(array(Field_1::$state), array('n')),
										 'where'=> $this->sql->where(array('where'), array(Field_1::$name), array($post['kind']), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$scheduling_log, '排程設定_關閉排程_' . $post['kind'], $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$scheduling_log, '排程設定_關閉排程_' . $post['kind'], $this->sql->get_time(1), '')),
										 'kind'=> 2));
			//執行
			if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
				return 3;
			} else {
				return 5;
			}
		}
		
		//啟用排程
		$this->sql->add_static(array('table'=> Table_1::$scheduling_log,
									 'select'=> $this->sql->field(array(Field_1::$state, Field_5::$day), array('y', null)),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$name), array($post['kind']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$scheduling_log, '排程設定_啟用排程_' . $post['kind'], $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$scheduling_log, '排程設定_啟用排程_' . $post['kind'], $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return $this->excute_scheduling($post);
		} else {
			return 5;
		}
	}
	
	/*
	 * 執行排程
	 * $post 資料
	 */
	private function excute_scheduling($post) {
		ignore_user_abort(true);
		set_time_limit(0);
		
		$ch = curl_init();
		
		$options = array(
				CURLOPT_URL => base_url() . 'index.php/operate/' . $post['kind'],
				CURLOPT_HEADER => false,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT=>1,
				CURLOPT_USERAGENT => "Google Bot",
				CURLOPT_FOLLOWLOCATION => true
		);
		
		curl_setopt_array($ch, $options);
		curl_exec($ch);

		return 2;
	}
	
	/*
	 * 繳費帳單匯入排程
	 * $user 使用者
	 */
	public function pay($user) {
		$contract = array();
		$now_month 	= date("n");
		$now_day 	= date("j");
		$now_week 	= date("N");
		
		//查詢需要抓取FTP檔案的業者
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_4::$publish, Field_4::$publish_week, Field_4::$publish_day, Field_4::$publish_month, Field_4::$ftp_ip, Field_4::$ftp_account, Field_4::$ftp_password, Field_4::$ftp_path . ' AS path'), 'function'),
																		 'from' => Table_1::$trader_contract,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'result_array');
		//篩選出符合條件的資料
		foreach($sql_result as $data) {
			if($data['ftp_ip'] == '') continue;
			if(file_exists('./resources/upload/pay_bill/' . $data['trader_code'] . $data['bill_kind_code'] . '/' . $data['trader_code'] . $data['bill_kind_code'] . date("Y") . date("n") . date("j") . '.csv')) continue;
			
			switch($data['publish']) {
				case 1:
					if($now_week == $data['publish_week']) array_push($contract, $data);
					break;
				case 2:
					if($now_day == $data['publish_day']) array_push($contract, $data);
					break;
				case 3:
					if($now_day == $data['publish_day'] && $now_month == $data['publish_month']) array_push($contract, $data);
					break;
				case 4:
					array_push($contract, $data);
					break;
			}
			continue;
		}

		//開始抓取及匯入帳單
	 	$import = $this->ftp_bill($contract, 'pay_bill', 1, $user);
		
	
		//開始匯入
		foreach($import as $data) {
			$get['trader'] = $data['trader_code'];
			$get['bill_kind'] = $data['bill_kind_code'];
			$file['file_name'] = $data['file_name'];
			$file['full_path'] = $_SERVER['DOCUMENT_ROOT'] . '/Billez_code/resources/upload/pay_bill/' . $data['trader_code'] . $data['bill_kind_code'] . '/' . $data['file_name'];

			$this->bill_import_model->import_pay($file, $get, $user);
		}
		$this->sql->clear_static();
		//處理完之後進入睡眠,但是每個十秒檢查一次是否已經關閉,否則容易出現多個執行緒
		$this->import_slepp('pay');
	}
	
	/*
	 * 抓取ftp帳單
	 * $contract 業者合約資料
	 * $kind 匯入種類
	 * $path 資料夾
	 * $import_kind 匯入種類
	 * $user 使用者
	 */
	private function ftp_bill($contract, $path, $import_kind, $user) {
		$import = array();
		
		foreach($contract as $data) {
			if(!file_exists('./resources/upload/' . $path . '/' . $data['trader_code'] . $data['bill_kind_code']))
				mkdir('./resources/upload/' . $path . '/' . $data['trader_code'] . $data['bill_kind_code']);
			
			$data['file_name'] = $data['trader_code'] . $data['bill_kind_code'] . date("Y") . date("n") . date("j") . '.csv';

			//設置ftp資料
			$config['hostname'] = $data['ftp_ip'];
			$config['username'] = $data['ftp_account'];
			$config['password'] = $data['ftp_password'];
			$config['port']     = 21;
			$config['passive']  = FALSE;
			$config['debug']    = FALSE;
			
			$this->ftp->connect($config);
			$this->ftp->download($data['path'], './resources/upload/' . $path . '/' . $data['trader_code'] . $data['bill_kind_code'] . '/' . $data['file_name'], 'auto');

			if(!file_exists('./resources/upload/' . $path . '/' . $data['trader_code'] . $data['bill_kind_code'] . '/' . $data['file_name'])) {
				//新增匯入錯誤紀錄
				$this->sql->add_static(array('table'=> Table_1::$import_error_log,
											 'select'=> $this->sql->field(array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_2::$file_name, Field_2::$file_path, Field_2::$kind, Field_2::$reason, Field_2::$data, Field_2::$user, Field_2::$time, Field_2::$result),
																		 array($data['trader_code'], $data['bill_kind_code'], 'ftp', $data['path'], $import_kind, 1, '下載檔案時出現錯誤', $user['id'], $this->sql->get_time(1), 'n')),
											 'where'=> '',
											 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$import_error_log, '排程帳單匯入_新增匯入錯誤紀錄', $this->sql->get_time(1))),
											 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$import_error_log, '排程帳單匯入_新增匯入錯誤紀錄', $this->sql->get_time(1), '')),
											 'kind'=> 1));
				continue;
			}

			array_push($import, $data);
		}
		
		//執行
		$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind));
		$this->sql->clear_static();
		
		return $import;
	}
	
	/*
	 * 匯入的間隔時間處理
	 * kind 匯入種類 
	 */
	private function import_slepp($kind) {
		//查詢是否有暫停天數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$state, Field_5::$day), ''),
																		 'from' => Table_1::$scheduling_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$name), array($kind), array('')),
																		 'other' => '')), 'row_array');
		if(isset($sql_result['day'])) {
			$day = $sql_result['day'];
			$times = (86400 * $day) / 5;
			$this->loop_sleep($day, $times);
			$this->excute_scheduling(array('kind'=> $kind));
			exit();
		} else {
			if($sql_result['state'] == 'n') exit();
			
			//查詢系統抓取檔案間隔時間
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_4::$get_file_time), ''),
																			 'from' => Table_1::$system_set,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_3::$using), array('y'), array('')),
																			 'other' => '')), 'row_array');
			$get_file_time = $sql_result['get_file_time'];
			
			while($get_file_time) {
				//查詢暫停天數是否存在
				$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$state, Field_5::$day), ''),
																				 'from' => Table_1::$scheduling_log,
																				 'join'=> '',
																				 'where' => $this->sql->where(array('where'), array(Field_1::$name), array($kind), array('')),
																				 'other' => '')), 'row_array');
				if(isset($sql_result['day'])) {
					$day = $sql_result['day'];
					$times = (86400 * $day) / 5;
					$this->loop_sleep($day, $times);
					$this->excute_scheduling(array('kind'=> $kind));
					exit();
				}

				if($sql_result['state'] == 'n') exit();
				
				sleep(5);
				$get_file_time -= 5;
			}
		
			$this->excute_scheduling(array('kind'=> $kind));
			exit();
		}
	}
	
	/*
	 * 睡眠迴圈
	 * $day	暫停天數
	 * $times 總睡眠次數
	 */
	private function loop_sleep($day, $times) {
		for($i = 0; $i < $times; $i++) {
			//查詢暫停天數是否還存在
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$state, Field_5::$day), ''),
																			 'from' => Table_1::$scheduling_log,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_1::$name), array($kind), array('')),
																			 'other' => '')), 'row_array');
			if(isset($sql_result['day'])) {
				//若是累積起來有一天則更新暫停日期
				if(($i * 5) % 86400 == 0) {
					$day--;
					if($day == 0) {
						$sql_result['state'] = 'y';
						$day = null;
					}
		
					$this->sql->add_static(array('table'=> Table_1::$scheduling_log,
												 'select'=> $this->sql->field(array(Field_1::$state, Field_5::$day, Field_1::$update_user, Field_1::$update_time), array($sql_result['state'], $day, $user['id'], $this->sql->get_time(1))),
												 'where'=> $this->sql->where(array('where'), array(Field_1::$name), array($kind), array('')),
												 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$scheduling_log, '匯入排成_減少暫停天數', $this->sql->get_time(1))),
												 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$scheduling_log, '匯入排成_減少暫停天數', $this->sql->get_time(1), '')),
												 'kind'=> 2));
					//執行
					$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind));
				}
					
				$this->sql->clear_static();
				sleep(5);
			} else {
				return true;
			}
		}
	}
	
	/*
	 * 入帳帳單匯入排程
	 * $user 使用者
	 */
	public function receive($user) {
		$contract = array();
		$now_month 	= date("n");
		$now_day 	= date("j");
		$now_week 	= date("N");
	
		//查詢需要抓取FTP檔案的業者
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_4::$enter, Field_4::$enter_week, Field_4::$enter_day, Field_4::$enter_month, Field_4::$ftp_ip, Field_4::$ftp_account, Field_4::$ftp_password, Field_4::$ftp_receive_path . ' AS path'), 'function'),
																		 'from' => Table_1::$trader_contract,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'result_array');
		//篩選出符合條件的資料
		foreach($sql_result as $data) {
			if($data['ftp_ip'] == '') continue;
			if(file_exists('./resources/upload/receive_bill/' . $data['trader_code'] . $data['bill_kind_code'] . '/' . $data['trader_code'] . $data['bill_kind_code'] . date("Y") . date("n") . date("j") . '.csv')) continue;
				
			switch($data['enter']) {
				case 1:
					if($now_week == $data['enter_week']) array_push($contract, $data);
					break;
				case 2:
					if($now_day == $data['enter_day']) array_push($contract, $data);
					break;
				case 3:
					if($now_day == $data['enter_day'] && $now_month == $data['enter_month']) array_push($contract, $data);
					break;
				case 4:
					array_push($contract, $data);
					break;
			}
			continue;
		}
	
		//開始抓取及匯入帳單
		$import = $this->ftp_bill($contract, 'receive_bill', 2, $user);
	
		//開始匯入
		foreach($import as $data) {
			$get['trader'] = $data['trader_code'];
			$get['bill_kind'] = $data['bill_kind_code'];
			$file['file_name'] = $data['file_name'];
			$file['full_path'] = $_SERVER['DOCUMENT_ROOT'] . '/Billez_code/resources/upload/receive_bill/' . $data['trader_code'] . $data['bill_kind_code'] . '/' . $data['file_name'];
	
			echo $this->bill_import_model->import_receive($file, $get, $user);
		}
	
		$this->sql->clear_static();
		//處理完之後進入睡眠,但是每個十秒檢查一次是否已經關閉,否則容易出現多個執行緒
		$this->import_slepp('receive');
	}
	
	/*
	 * 暫停天數
	 * $post 資料
	 * $user 使用者
	 */
	public function stop_day($post, $user) {
		//更新停止天數及關閉排程
		$this->sql->add_static(array('table'=> Table_1::$scheduling_log,
									 'select'=> $this->sql->field(array(Field_5::$day, Field_1::$update_user, Field_1::$update_time), array($post['day'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where', 'or_where'), array(Field_1::$name, Field_1::$name), array('pay', 'receive'), array('', '')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$scheduling_log, '排程設定_新增暫停天數', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$scheduling_log, '排程設定_新增暫停天數', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'reload';
		} else {
			return 1;
		}
	}
	
	/*
	 * 推播未讀取帳單流程
	 * $user 使用者
	 */
	public function push($user) {
		//查詢目前系統推播次數上限
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$push_times, Field_4::$repeat_push_time), ''),
																		 'from' => Table_1::$system_set,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_3::$using), array('y'), array('')),
																		 'other' => '')), 'row_array');
		$push_times = $sql_result['push_times'];
		$repeat_push_time = $sql_result['repeat_push_time'];
		$read = Field_1::$read;
		$gcm = 'gcm_2';
		$event = 2;
		
		//查詢未超出次數的未讀取繳費帳單會員
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$push_state,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$times . ' <=', $read), array($push_times, 'n'), array('')),
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$action_member = $sql_result;
		
		foreach($action_member as $data) {
			//收集推播必須資料
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$mobile_phone, Field_1::$mobile_phone_id), ''),
																			 'from' => Table_1::$action_member,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($data['id']), array('')),
																			 'other' => '')), 'row_array');
			//將要推播的訊息丟進推播變數
			$this->push->add_static(array('id' => $data['id'],
										  'moblie_phone' => $sql_result['mobile_phone'],
										  'moblie_phone_id' => $sql_result['mobile_phone_id'],
										  'billez_code' => '',
										  'result' => '',
										  'message' => ''));
			//查詢推播次數
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$times), ''),
																			 'from' => Table_1::$push_state,
																			 'join'=> '',
																			 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($data['id']), array('')),
																		 	 'other' => '')), 'row_array');						
			//更新推播狀態
			$this->sql->add_static(array('table'=> Table_1::$push_state,
										 'select'=> $this->sql->field(array(Field_1::$times, Field_1::$update_user, Field_1::$update_time), array($sql_result['times'] + 1, $user['id'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($data['id']), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$push_state, '推播排程_更新推播次數', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$push_state, '推播排程_更新推播次數', $this->sql->get_time(1), '')),
										 'kind'=> 2));
		} 	
			
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			$route_data['sub_param'] = 1;
			$route_data['id'] = $user['id'];
			$this->push_model->bill_push($route_data, array('message' => $gcm, 'event' => $event, 'record' => '帳單排程_最新帳單推播通知', 'code' => '05'));
			$this->sql->clear_static();
			$this->push->clear_push_list();
		}
		
		while($repeat_push_time) {
			//查詢推播排程狀態
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$state), ''),
																			 'from' => Table_1::$scheduling_log,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_1::$name), array(), array('')),
																			 'other' => '')), 'row_array');
			if($sql_result['state'] == 'n') exit();

			sleep(10);
			$repeat_push_time -= 5;
		}
		
		$this->excute_scheduling(array('kind'=> 'push'));
		exit();
	}
	
	/*
	 * 可能帳單配對排程
	 * $user 使用者
	 */
	public function possible($user) {
		$action_member = array();
		
		//查詢所有已成功註冊的會員編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$state), array('2'), array('')),
																		 'other' => '')), 'result_array');
		foreach($sql_result as $route_data) {
			$route_data['scheduling'] = 'y';
			if(count($this->bill_model->possible_bill($route_data))) array_push($action_member, $route_data['id']);
		}

		foreach($action_member as $id) {
			//查詢會員手機及手機ID
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$mobile_phone, Field_1::$mobile_phone_id), ''),
																			 'from' => Table_1::$action_member,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($id), array('')),
																			 'other' => '')), 'row_array');
			//將要推播的訊息丟進推播變數
			$this->push->add_static(array('id' => $id,
										  'moblie_phone' => $sql_result['mobile_phone'],
										  'moblie_phone_id' => $sql_result['mobile_phone_id'],
										  'billez_code' => '',
										  'result' => '',
										  'message' => ''));
		}
		
		$event = 4;
		$gcm = 'gcm_4';
		$route_data['sub_param'] = 1;
		$route_data['id'] = $user['id'];
		$this->push_model->bill_push($route_data, array('message' => $gcm, 'event' => $event, 'record' => '可能帳單配對排程_可能帳單推播通知', 'code' => '05'));
		$this->sql->clear_static();
		$this->push->clear_push_list();
		
		//查詢配對可能帳單間隔時間
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_4::$possible_bill_time), ''),
																		 'from' => Table_1::$system_set,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_3::$using), array('y'), array('')),
																		 'other' => '')), 'row_array');
		$possible_bill_time = $sql_result['possible_bill_time'];
		
		while($possible_bill_time) {
			//查詢可能帳單配對排程狀態
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$state), ''),
																			 'from' => Table_1::$scheduling_log,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_1::$name), array('possible'), array('')),
																			 'other' => '')), 'row_array');
			if($sql_result['state'] == 'n') exit();
			
			sleep(10);
			$possible_bill_time -= 5;
		}
		
		$this->excute_scheduling(array('kind'=> 'possible'));
		exit();
	}
}//end