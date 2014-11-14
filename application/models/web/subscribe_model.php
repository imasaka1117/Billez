<?php

class Subscribe_model extends CI_Model {
	/*
	 * 更改業者代收機構狀態
	 * $post 查詢條件資料
	 * $user 使用者
	 */
	public function update_trader_machinery($post, $user) {
		//更新業者代收機構及合約
		$this->sql->add_static(array('table'=> Table_1::$trader_machinery,
									 'select'=> $this->sql->field(array(Field_2::$machinery_code, Field_4::$machinery_contract, Field_1::$update_user, Field_1::$update_time), array($post['machinery'], $post['machinery_contract'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where','where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($post['trader'], $post['bill_kind']), array('', '')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$trader_machinery, '業者代收機構更改_更改業者代收機構及合約', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$trader_machinery, '業者代收機構更改_更改業者代收機構及合約', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'reload';
		} else {
			return 1;
		}
	}
	/*
	 * 更改業者訂閱狀態
	 * $post 查詢條件資料
	 * $user 使用者
	 */
	public function update_state($post, $user) {
		if(strlen($post['trader']) > 4) $post['trader'] = '';
		if(strlen($post['bill_kind']) > 2) $post['bill_kind'] = '';
		if(strlen($post['state']) > 1) $post['state'] = '';
		
		//查詢有訂閱該業者帳單的會員資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$subscribe . '.' . Field_1::$id, Field_1::$mobile_phone, Field_1::$mobile_phone_id), 'function'),
																		 'from' => Table_1::$subscribe,
																		 'join'=> $this->sql->join(array(Table_1::$action_member), array(Table_1::$subscribe . '.' . Field_1::$id . ' = ' . Table_1::$action_member . '.' . Field_1::$id), array('')),
																		 'where' => $this->sql->where(array('like'), array(Field_3::$subscribe_code), array($post['trader'] . $post['bill_kind']), array('after')),
																		 'other' => '')), 'result_array');
		//蒐集推播需要資料
		foreach($sql_result as $data) {
			//將要推播的訊息丟進推播變數
			$this->push->add_static(array('id' => $data['id'],
										  'moblie_phone' => $data['mobile_phone'],
										  'moblie_phone_id' => $data['mobile_phone_id'],
										  'billez_code' => '',
										  'result' => '',
										  'message' => ''));
		}
		
		//更新訂閱狀態
		$this->sql->add_static(array('table'=> Table_1::$subscribe,
									 'select'=> $this->sql->field(array(Field_1::$state, Field_1::$update_user, Field_1::$update_time), array($post['state'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('like'), array(Field_3::$subscribe_code), array($post['trader'] . $post['bill_kind']), array('after')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$subscribe, '業者訂閱狀態更改_更改訂閱狀態及推播通知', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$subscribe, '業者訂閱狀態更改_更改訂閱狀態及推播通知', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			$route_data['sub_param'] = 1;
			$route_data['id'] = $user['id'];
			$this->push_model->bill_push($route_data, array('message' => $post['trader'] . $post['bill_kind'], 'event' => 3, 'record' => '帳單推播_業者訂閱狀態改變', 'code' => '05'));
			return 'reload';
		} else {
			return 1;
		}
	}
	
	/*
	 * 查詢訂閱資料
	 * $post 查詢條件資料
	 */
	public function search_subscribe($post) {
		if(strlen($post['trader']) > 4) $post['trader'] = '';
		if(strlen($post['bill_kind']) > 2) $post['bill_kind'] = '';

		//查詢訂閱列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$subscribe_code), ''),
																		 'from' => Table_1::$subscribe,
																		 'join'=> $this->sql->join(array(Table_1::$action_member), array(Table_1::$subscribe . '.' . Field_1::$id . ' = ' . Table_1::$action_member . '.' . Field_1::$id), array('')),
																		 'where' => $this->sql->where_search(array(Table_1::$subscribe . '.' . Field_1::$id, 'CONCAT(' . Field_1::$last_name . ',' . Field_1::$first_name . ')', Field_1::$email, Field_3::$subscribe_code, Field_3::$subscribe_code), array($post['id'], $post['name'], $post['email'], $post['subscribe'], $post['trader'] . $post['bill_kind'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
		
		//查詢訂閱列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$action_member . '.' . Field_1::$id . ' AS aid', Field_3::$subscribe_code, 'CONCAT(' . Field_1::$last_name . ',' . Field_1::$first_name . ') AS name', Field_1::$email, Field_3::$send_condition, Field_2::$time, 'IF(' . Table_1::$subscribe . '.' . Field_1::$state . ' = 1, "處理中", IF(' . Table_1::$subscribe . '.' . Field_1::$state . ' = 2, "訂閱中", "取消中")) AS ' . Field_1::$state), 'function'),
																		 'from' => Table_1::$subscribe,
																		 'join'=> $this->sql->join(array(Table_1::$action_member), array(Table_1::$subscribe . '.' . Field_1::$id . ' = ' . Table_1::$action_member . '.' . Field_1::$id), array('')),
																		 'where' => $this->sql->where_search(array(Table_1::$subscribe . '.' . Field_1::$id, 'CONCAT(' . Field_1::$last_name . ',' . Field_1::$first_name . ')', Field_1::$email, Field_3::$subscribe_code, Field_3::$subscribe_code), array($post['id'], $post['name'], $post['email'], $post['subscribe'], $post['trader'] . $post['bill_kind'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		$subscribe = $sql_result;
		$count = count($sql_result);
		
		for($i = 0; $i < $count; $i++) {
			//查詢業者帳單名稱
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$trader_code . '.' . Field_1::$name . ' AS trader_name', Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind_name'), 'function'),
																			 'from' => Table_1::$trader_bill,
																			 'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code), array(Table_1::$trader_bill . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_code . '.' . Field_1::$code, Table_1::$trader_bill . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$bill_kind_code . '.' . Field_1::$code), array('', '')),
																			 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array(substr($subscribe[$i]['subscribe_code'], 0, 4), substr($subscribe[$i]['subscribe_code'], 4, 2)), array('', '')),
																			 'other' => '')), 'row_array');
			$subscribe[$i]['trader_name'] = $sql_result['trader_name'];
			$subscribe[$i]['bill_kind_name'] = $sql_result['bill_kind_name'];
		}
		
		return $this->option->table($subscribe, array('會員編號', '訂閱碼', '會員名稱', '會員電子郵件', '實體帳單寄送次數', '訂閱時間', '訂閱狀態', '業者', '帳單種類'), base_url() . Param::$index_url . 'subscribe/update_web') . $this->option->page($page_count, $post['page']);
	}
}//end 	