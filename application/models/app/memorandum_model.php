<?php

class Memorandum_model extends CI_Model {
	/*
	 * 帳單備忘錄起點函式
	 * $route_data從APP來的參數
	 */
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '9_1':
				return $this->insert_data($route_data);
				break;
			case '9_2':
				return $this->alter_data($route_data);
				break;
		}
	}
	
	/*
	 * 新增備忘錄資料
	 * $route_data 所需參數資料
	 */
	public function insert_data($route_data) {
		if($route_data['trader_name'] == '') $route_data['trader_name'] = 'blank';
		
		//將額外資料組成一個字串
		$data = $route_data['bill_kind'] . ',' . $route_data['trader_name'] . ',' . $route_data['data'];
		
		//查詢行動會員額外資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('*'), ''),
																		 'from' => Table_1::$action_member_data,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');
		$action_member_data = $sql_result;
		$number = $action_member_data['data_number'] + 1;

		if(isset($action_member_data['data' . $number])) {
			//更新額外資料在已經存在的欄位
			$this->sql->add_static(array('table'=> Table_1::$action_member_data,
										 'select'=> $this->sql->field(array('data' . $number, Field_1::$data_number, Field_1::$update_user, Field_1::$update_time), array($data, $number, $route_data['id'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$action_member_data, '帳單備忘錄_更新備忘錄資料在已存在欄位', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$action_member_data, '帳單備忘錄_更新備忘錄資料在已存在欄位', $this->sql->get_time(1), '')),
										 'kind'=> 2));
		} else {
			//修改資料表,動態增加欄位數
			$this->db->query('ALTER TABLE `action_member_data` ADD `data' . $number . '` varchar(255) COLLATE utf8_bin DEFAULT NULL');
				
			//更新額外資料在剛新增好的欄位
			$this->sql->add_static(array('table'=> Table_1::$action_member_data,
										 'select'=> $this->sql->field(array('data' . $number, Field_1::$data_number, Field_1::$update_user, Field_1::$update_time), array($data, $number, $route_data['id'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$action_member_data, '帳單備忘錄_更新備忘錄資料在新創造的欄位', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$action_member_data, '帳單備忘錄_更新備忘錄資料在新創造的欄位', $this->sql->get_time(1), '')),
										 'kind'=> 2));
		}
		
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			//成功回傳狀態碼
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
				
		}
		
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '02'), $route_data['private_key'], ''));
	}
	
	/*
	 * 修改備忘錄資料
	 * 將資料比對後再修改進入
	 * 也要將舊資料寫入修改記錄
	 * $route_data
	 */
	public function alter_data($route_data) {
		if($route_data['trader_name'] == '') $route_data['trader_name'] = 'blank';
		
		//查詢修改記錄備忘錄資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$bill_memo), ''),
																		 'from' => Table_1::$action_member_alter_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id, Field_1::$frequency), array($route_data['id'], 1), array('')),
																		 'other' => '')), 'row_array');
		//組合成新的修改記錄
		$alter_log = $sql_result['bill_memo'] . ',' . $route_data['old_data'];
		$old_data = $route_data['bill_kind'] . ',' . $route_data['old_trader_name'] . ',' . $route_data['old_data'];
		$new_data = $route_data['bill_kind'] . ',' . $route_data['trader_name'] . ',' . $route_data['data'];
		
		//查詢需要修改的欄位資料,用舊資料去查詢
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('*'), ''),
																		 'from' => Table_1::$action_member_data,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');		
		foreach($sql_result as $item => $value) {
			if($value == $old_data) {
				$col = $item;
				break;
			}
		}
		
		//更新帳單備忘錄修改記錄
		$this->sql->add_static(array('table'=> Table_1::$action_member_alter_log,
									 'select'=> $this->sql->field(array(Field_1::$bill_memo, Field_1::$update_user, Field_1::$update_time), array($alter_log, $route_data['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where', 'where'), array(Field_1::$id, Field_1::$frequency), array($route_data['id'], 1), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$action_member_alter_log, '帳單備忘錄_新增行動會員修改備忘錄記錄', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$action_member_alter_log, '帳單備忘錄_新增行動會員修改備忘錄記錄', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//更新備忘錄額外資料
		$this->sql->add_static(array('table'=> Table_1::$action_member_data,
									 'select'=> $this->sql->field(array($col, Field_1::$update_user, Field_1::$update_time), array($new_data, $route_data['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$action_member_data, '帳單備忘錄_更新備忘錄資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$action_member_data, '帳單備忘錄_更新備忘錄資料', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			//成功回傳狀態碼
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
		}
		
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '02'), $route_data['private_key'], ''));	
	}
}//end