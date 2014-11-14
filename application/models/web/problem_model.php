<?php

class Problem_model extends CI_Model {
	/*
	 * 新增問題
	 * $post 查詢資料
	 * $user 使用者
	 */
	public function insert($post, $user) {
		//確認提問者帳號是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$user_list,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$email), array($post['email']), array('')),
																		 'other' => '')), 'num_rows');
		//若存在回傳代碼
		if(!$sql_result) return 1;
	
		//查詢等級最大編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$id . ') AS max'), 'function'),
																		 'from' => Table_1::$problem_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		$id = $this->create->id('PR', $sql_result['max']);
	
		//新增問題
		$this->sql->add_static(array('table'=> Table_1::$problem_log,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_3::$problem, Field_3::$asker, Field_3::$scope, Field_1::$state, Field_3::$ask_time, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($id, $post['problem'], $post['email'], $post['scope'], 'n', $this->sql->get_time(1), $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$problem_log, '新增問題_新增問題', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$problem_log, '新增問題_新增問題', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'reload';
		} else {
			return 2;
		}
	}
	
	/*
	 * 更新問題資料
	* $post 查詢資料
	* $user 使用者
	*/
	public function update($post, $user) {
		//更新問題回覆內容
		$this->sql->add_static(array('table'=> Table_1::$problem_log,
									 'select'=> $this->sql->field(array(Field_1::$state, Field_3::$answer, Field_3::$response, Field_3::$reply_time, Field_1::$update_user, Field_1::$update_time), array('y', $post['answer'], $post['response'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('', '')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$problem_log, '查詢問題_更新問題回覆', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$problem_log, '查詢問題_更新問題回覆', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			//查詢問題回報目前使用的電子郵件版本
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$server_name, Field_2::$server_port, Field_2::$account, Field_1::$password, Field_2::$send_email, Field_2::$send_name, Field_2::$subject, Field_2::$body), ''),
																			 'from' => Table_1::$email_form,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_2::$form_kind, Field_1::$state), array(4, 'y'), array('')),
																			 'other' => '')), 'row_array');
			$route_data['email'] = $post['asker'];
			$route_data['problem'] = $post['problem'];
			$route_data['response'] = $post['response'];
			$route_data['id'] = $user['id'];
			$route_data['sub_param'] = '0';
			
			$this->email_model->send_email($route_data, $sql_result, array('record' => '查詢問題_新增寄發問題回覆電子郵件紀錄', 'event' => 4, 'success' => '02', 'fail' => '03'));	
			return 'reload';
		} else {
			return 1;
		}
	}
	
	/*
	 * 查詢問題資料
	 * $post 查詢資料
	 */
	public function search_data($post) {
		//查詢問題資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Field_3::$problem, Field_3::$asker, 'IF (' . Field_3::$scope . ' = "1", "行動會員", IF (' . Field_3::$scope . ' = "2", "一般會員", IF (' . Field_3::$scope . ' = "3", "業者", "代收機構"))) AS ' . Field_3::$scope, 'IF (' . Field_1::$state . ' = "n", "未回覆", "已回覆") AS ' . Field_1::$state, Field_3::$ask_time, Field_3::$star, Field_4::$page, Field_3::$reply_time, Field_3::$answer, Field_3::$response), 'function'),
																		 'from' => Table_1::$problem_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 查詢問題資料
	 * $post 查詢條件資料
	 */
	public function search($post) {	
		if(strlen($post['state']) > 1) $post['state'] = '';
		if(strlen($post['scope']) > 1) $post['scope'] = '';
		if(strlen($post['ask_time']) > 10) $post['ask_time'] = '';

		//查詢問題列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$problem_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_3::$asker, Field_3::$ask_time, Field_3::$scope, Field_1::$state), array($post['id'], $post['asker'], $post['ask_time'], $post['scope'], $post['state'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
		
		//查詢問題列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Field_3::$problem, Field_3::$asker, 'IF(' . Field_1::$state . ' = "y", "已回覆", "未回覆") AS ' . Field_1::$state, Field_3::$ask_time), 'function'),
																		 'from' => Table_1::$problem_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_3::$asker, Field_3::$ask_time, Field_3::$scope, Field_1::$state), array($post['id'], $post['asker'], $post['ask_time'], $post['scope'], $post['state'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		
		return $this->option->table($sql_result, array('問題編號', '問題描述', '提問者', '問題狀態', '提問時間'), base_url() . Param::$index_url . 'problem/update_web') . $this->option->page($page_count, $post['page']);
	}
}//end 	