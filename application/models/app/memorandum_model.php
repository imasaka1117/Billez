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
		$app = '9_1';
		
		//將額外資料組成一個字串
		$data = $route_data['bill_kind'] . ',' . $route_data['trader_name'] . ',' . $route_data['data'];
		
		//查詢行動會員額外資料
		$sql_select = $this->sql->select(array('*'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member_data', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$action_member_data = $sql_result;
		
		if(isset($action_member_data['data' . $action_member_data['data_number'] + 1])) {
			//更新額外資料在已經存在的欄位
			array_push(Sql::$table, 'action_member_data');
			array_push(Sql::$select, $this->sql->field(array('data' . $action_member_data['data_number'] + 1, 'data_number', 'update_user', 'update_time'), array($data, $action_member_data['data_number'] + 1, $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'action_member_data', '帳單備忘錄新增會員已有欄位額外資料', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'action_member_data', '帳單備忘錄新增會員已有欄位額外資料', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
		} else {
			//修改資料表,動態增加欄位數
			$this->db->query('ALTER TABLE `action_member_data` ADD `data' . $action_member_data['data_number'] + 1 . '` varchar(255) COLLATE utf8_bin DEFAULT NULL');
				
			//更新額外資料在剛新增好的欄位
			array_push(Sql::$table, 'action_member_data');
			array_push(Sql::$select, $this->sql->field(array('data' . $action_member_data['data_number'] + 1, 'data_number', 'update_user', 'update_time'), array($data, $action_member_data['data_number'] + 1, $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'action_member_data', '帳單備忘錄新增會員新欄位額外資料', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'action_member_data', '帳單備忘錄新增會員新欄位額外資料', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
		}
		
		//執行更新
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
			$json_data = $this->json->encode_json($app, '9_101');
		} else {
			$json_data = $this->json->encode_json($app, '9_102');
		}
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json(1, $encode_data);
	}
	
	/*
	 * 修改備忘錄資料
	 * 將資料比對後再修改進入
	 * 也要將舊資料寫入修改記錄
	 * $route_data
	 */
	public function alter_data($route_data) {
		$app = '9_2';
		
		//查詢修改記錄備忘錄資料
		$sql_select = $this->sql->select(array('bill_memo'), '');
		$sql_where = $this->sql->where(array('where', 'where'), array('id', 'frequency'), array($route_data['id'], 1), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member_alter_log', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		
		//組合成新的修改記錄
		$alter_log = $sql_result['bill_memo'] . ',' . $route_data['old_data'];
		$old_data = $route_data['bill_kind'] . ',' . $route_data['old_trader_name'] . ',' . $route_data['old_data'];
		$new_data = $route_data['bill_kind'] . ',' . $route_data['trader_name'] . ',' . $route_data['data'];
		
		//查詢需要修改的欄位資料,用舊資料去查詢
		$sql_select = $this->sql->select(array('mobile_phone'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		
		foreach($sql_result as $item => $value) {
			if($value == $old_data) {
				$col = $item;
				break;
			}
		}
		
		//更新帳單備忘錄修改記錄
		array_push(Sql::$table, 'action_member_alter_log');
		array_push(Sql::$select, $this->sql->field(array('bill_memo', 'update_user', 'update_time'), array($alter_log, $route_data['id'], $this->sql->get_time(1))));
		array_push(Sql::$where, $this->sql->where(array('where', 'where'), array('id', 'frequency'), array($route_data['id'], 1), array('')));
		array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'action_member_alter_log', '帳單備忘錄更新修改記錄資料', $this->sql->get_time(1))));
		array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'action_member_alter_log', '帳單備忘錄更新修改記錄資料', $this->sql->get_time(1), '')));
		array_push(Sql::$kind, 2);
		
		//更新備忘錄額外資料
		array_push(Sql::$table, 'action_member_data');
		array_push(Sql::$select, $this->sql->field(array($col, 'update_user', 'update_time'), array($new_data, $route_data['id'], $this->sql->get_time(1))));
		array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
		array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'action_member_data', '帳單備忘錄更新備忘錄資料', $this->sql->get_time(1))));
		array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'action_member_data', '帳單備忘錄更新備忘錄資料', $this->sql->get_time(1), '')));
		array_push(Sql::$kind, 2);
		
		//執行更新
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
			$json_data = $this->json->encode_json($app, '9_201');
		} else {
			$json_data = $this->json->encode_json($app, '9_202');
		}
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json(1, $encode_data);		
	}
}//end