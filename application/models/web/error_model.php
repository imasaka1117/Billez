<?php

class Error_model extends CI_Model {
	/*
	 * 更新帳單匯入錯誤資料
	 * $post 查詢資料
	 * $user 使用者
	 */
	public function update_bill_import($post, $user) {
		//更新帳單匯入錯誤
		$this->sql->add_static(array('table'=> Table_1::$import_error_log,
									 'select'=> $this->sql->field(array(Field_2::$result, Field_1::$update_user, Field_1::$update_time), array('y', $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_2::$time), array($post['time']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$import_error_log, '查詢匯入錯誤資料_更新處理狀態', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$import_error_log, '查詢匯入錯誤資料_更新處理狀態', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'reload';
		} else {
			return 1;
		}
	}
	
	/*
	 * 查詢帳單匯入錯誤資料
	 * $post 查詢資料
	 */
	public function search_bill_import_data($post) {
		//查詢帳單匯入錯誤資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$time, Field_2::$user, Table_1::$trader_code . '.' . Field_1::$name . ' AS trader_name', Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind_name', Field_2::$file_name, Field_2::$file_path, 'IF (' . Field_2::$kind . ' = "1", "繳費帳單", "入帳帳單") AS ' . Field_2::$kind, 'IF (' . Field_2::$reason . ' = "1", "上傳錯誤", IF (' . Field_2::$reason . ' = "2", "繳費帳單格式錯誤", IF (' . Field_2::$reason . ' = "3", "帳單資料數不正確", IF (' . Field_2::$reason . ' = "4", "帳單資料有空白", IF (' . Field_2::$reason . ' = "5", "帳單標題與格式不同 ", "寫入資料庫出錯"))))) AS ' . Field_2::$reason, Field_2::$result, Field_2::$data), 'function'),
																		 'from' => Table_1::$import_error_log,
																		 'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code), array(Table_1::$import_error_log . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_code . '.' . Field_1::$code, Table_1::$import_error_log . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$bill_kind_code . '.' . Field_1::$code), array('', '')),
																		 'where' => $this->sql->where(array('where'), array(Field_2::$time), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 查詢帳單匯入錯誤資料
	 * $post 查詢條件資料
	 */
	public function search_bill_import($post) {	
		if(strlen($post['trader']) > 4) $post['trader'] = '';
		if(strlen($post['bill_kind']) > 2) $post['bill_kind'] = '';
		if(strlen($post['import_kind']) > 1) $post['import_kind'] = '';
		if(strlen($post['state']) > 1) $post['state'] = '';
		if(strlen($post['time']) > 10) $post['time'] = '';

		//查詢帳單匯入錯誤列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$time), ''),
																		 'from' => Table_1::$import_error_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_2::$kind, Field_2::$result, Field_2::$time), array($post['trader'], $post['bill_kind'], $post['import_kind'], $post['state'], $post['time'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
		
		//查詢帳單匯入錯誤資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$time . ' AS id', Table_1::$trader_code . '.' . Field_1::$name . ' AS trader_name', Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind_name', Field_2::$file_name, 'IF (' . Field_2::$kind . ' = "1", "繳費帳單", "入帳帳單") AS ' . Field_2::$kind, 'IF (' . Field_2::$reason . ' = "1", "上傳錯誤", IF (' . Field_2::$reason . ' = "2", "繳費帳單格式錯誤", IF (' . Field_2::$reason . ' = "3", "帳單資料數不正確", IF (' . Field_2::$reason . ' = "4", "帳單資料有空白", IF (' . Field_2::$reason . ' = "5", "帳單標題與格式不同 ", "寫入資料庫出錯"))))) AS ' . Field_2::$reason, Field_2::$user, 'IF (' . Field_2::$result . ' = "y", "已處理", "未處理") AS ' . Field_2::$result), 'function'),
																		 'from' => Table_1::$import_error_log,
																		 'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code), array(Table_1::$import_error_log . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_code . '.' . Field_1::$code, Table_1::$import_error_log . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$bill_kind_code . '.' . Field_1::$code), array('', '')),
																		 'where' => $this->sql->where_search(array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_2::$kind, Field_2::$result, Field_2::$time), array($post['trader'], $post['bill_kind'], $post['import_kind'], $post['state'], $post['time'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		
		return $this->option->table($sql_result, array('匯入時間', '業者', '帳單種類', '檔案名稱', '匯入種類', '錯誤原因', '匯入人員', '目前狀態'), base_url() . Param::$index_url . 'error/update_bill_import_web') . $this->option->page($page_count, $post['page']);
	}
	
	/*
	 * 查詢推播失敗資料
	 * $post 查詢條件資料
	 */
	public function search_push($post) {
		if(strlen($post['event']) > 1) $post['event'] = '';
		if(strlen($post['time']) > 10) $post['time'] = '';
	
		//查詢推播失敗列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$push_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$mobile_phone, Field_2::$event, Field_2::$time, Field_2::$result), array($post['id'], $post['mobile_phone'], $post['event'], $post['time'], '2')),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);

		//查詢推播失敗紀錄
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id . ' AS code', Field_1::$mobile_phone, Field_2::$time, Field_2::$event, Field_1::$message), 'function'),
																		 'from' => Table_1::$push_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$mobile_phone, Field_2::$event, Field_2::$time, Field_2::$result), array($post['id'], $post['mobile_phone'], $post['event'], $post['time'], '2')),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
	
		$push_data = array();
		foreach($sql_result as $data) {
			$data['event'] = $this->transform->push_event($data['event']);
			array_push($push_data, $data);
		}
	
		return $this->option->table($push_data, array('會員編號', '手機號碼', '時間', '推播種類', '失敗訊息'), base_url() . Param::$index_url . 'error/update_push_web') . $this->option->page($page_count, $post['page']);
	}
	
	/*
	 * 推播尚未讀取的會員
	 * $post 查詢條件資料
	 * $user 使用者
	 */
	public function push_not_read($post, $user) {
		//id指的是推播種類
		if($post['id'] == 1) {
			$read = Field_1::$read;
			$gcm = 'gcm_2';
			$event = 2;
		} else {
			$read = Field_3::$receive_read;
			$gcm = 'gcm_3';
			$event = 5;
		}
		
		//查詢繳費帳單通知或入帳帳單通知尚未讀取推播的會員編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$push_state,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array($read), array('n'), array('')),
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$push_count = count($sql_result);
		
		//蒐集推播需要資料
		foreach($sql_result as $data) {
			//查詢手機號碼和手機ID
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
		}

		$route_data['sub_param'] = 1;
		$route_data['id'] = $user['id'];
		$this->push_model->bill_push($route_data, array('message' => $gcm, 'event' => $event, 'record' => '查詢推播錯誤_推播尚未讀取帳單通知的會員', 'code' => '05'));
		return $push_count;
	}
	
	/*
	 * 查詢寄發電子郵件失敗資料
	 * $post 查詢條件資料
	 */
	public function search_email($post) {
		if(strlen($post['event']) > 1) $post['event'] = '';
		if(strlen($post['time']) > 10) $post['time'] = '';
	
		//查詢寄發電子郵件失敗列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$email_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$email, Field_2::$event, Field_1::$create_time, Field_2::$result), array($post['id'], $post['email'], $post['event'], $post['time'], '2')),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
	
		//查詢寄發電子郵件失敗紀錄
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id . ' AS code', Field_1::$email, Field_1::$create_time, Field_2::$event, Field_2::$file_name), 'function'),
																		 'from' => Table_1::$email_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$email, Field_2::$event, Field_1::$create_time, Field_2::$result), array($post['id'], $post['email'], $post['event'], $post['time'], '2')),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		$email_data = array();
		foreach($sql_result as $data) {
			$data['event'] = $this->transform->email_event($data['event']);
			array_push($email_data, $data);
		}
		
		return $this->option->table($email_data, array('會員編號', '電子郵件', '時間', '寄發種類', '附加檔案名稱'), base_url() . Param::$index_url . 'error/update_email_web') . $this->option->page($page_count, $post['page']);
	}
	
	/*
	 * 查詢寄發簡訊失敗資料
	 * $post 查詢條件資料
	 */
	public function search_sms($post) {
		if(strlen($post['event']) > 1) $post['event'] = '';
		if(strlen($post['time']) > 10) $post['time'] = '';
	
		//查詢寄發簡訊失敗列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$sms_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$mobile_phone, Field_2::$event, Field_1::$create_time, Field_2::$result), array($post['id'], $post['mobile_phone'], $post['event'], $post['time'], '2')),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
	
		//查詢寄發簡訊失敗紀錄
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id . ' AS code', Field_1::$mobile_phone, Field_1::$create_time, Field_2::$event, Field_2::$error_message), 'function'),
																		 'from' => Table_1::$sms_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$mobile_phone, Field_2::$event, Field_1::$create_time, Field_2::$result), array($post['id'], $post['mobile_phone'], $post['event'], $post['time'], '2')),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		$sms_data = array();
		foreach($sql_result as $data) {
			$data['event'] = $this->transform->sms_event($data['event']);
			array_push($sms_data, $data);
		}
	
		return $this->option->table($sms_data, array('會員編號', '手機號碼', '時間', '寄發種類', '錯誤訊息'), base_url() . Param::$index_url . 'error/update_sms_web') . $this->option->page($page_count, $post['page']);
	}
	
	/*
	 * 查詢系統錯誤資料
	 * $post 查詢條件資料
	 */
	public function search_system($post) {
		if(strlen($post['time']) > 10) $post['time'] = '';
	
		//查詢系統錯誤列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$user), ''),
																		 'from' => Table_1::$system_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$create_time), array($post['time'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
	
		//查詢系統錯誤紀錄
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_3::$db_message, Field_1::$create_time), ''),
																		 'from' => Table_1::$system_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$create_time), array($post['time'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		$system_data = array();
		foreach($sql_result as $data) {
			$data['operate'] = $this->transform->system_operate($data['operate']);
			$data['user'] = $this->transform->system_user($data['user']);
			array_push($system_data, $data);
		}
	
		return $this->option->table($system_data, array('操作', '操作者', '資料表', '操作目的', '錯誤訊息', '時間'), base_url() . Param::$index_url . 'error/update_sms_web') . $this->option->page($page_count, $post['page']);
	}
}//end 	