<?php

class Bill_model extends CI_Model {
	/*
	 * 查詢繳費帳單匯入紀錄列表
	 * $post	web傳來的參數
	 */
	public function search_import_receive_log($post) {
		if(strlen($post['trader']) > 4) $post['trader'] = '';
		if(strlen($post['bill_kind']) > 2) $post['bill_kind'] = '';
	
		//查詢入帳帳單匯入紀錄列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$batch_code), 'function'),
																		 'from' => Table_1::$bill_import_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$import_bill_kind), array(2)),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
	
		//查詢帳單列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$batch_code, Field_1::$year, Field_1::$month, Field_1::$import_time, Field_2::$file_name, 'IF (' . Field_1::$pushed . ' = "y", "是", "否") AS ' . Field_1::$pushed), 'function'),
																		 'from' => Table_1::$bill_import_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$import_bill_kind), array(2)),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		return $this->option->table($sql_result, array('批次碼', '年份', '月份', '匯入時間', '檔案名稱', '是否已推播'), base_url() . Param::$index_url . 'bill/import_receive_web') . $this->option->page($page_count, $post['page']);
	}
	
	/*
	 * 推播帳單
	 * $post web傳來的參數
	 * $user 使用者
	 */
	public function push_bill($post, $user) {
		$push_data = explode(',', $post['not_push_bill']);
		$push_data = array('trader_code'=>$push_data[0],'bill_kind_code'=>$push_data[1],'import_bill_kind'=>$push_data[2],'batch_code'=>$push_data[3],'year'=>$push_data[4],'month'=>$push_data[5]);
		
		if($push_data['import_bill_kind'] == 1) {
			$read = Field_1::$read;
			$gcm = 'gcm_2';
			$event = 2;
		} else {
			$read = Field_3::$receive_read;
			$gcm = 'gcm_3';
			$event = 5;
		}
		
		//查詢尚未推播的會員編號和帳單編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Table_1::$push_state . '.' . Field_1::$billez_code . ' AS ' . Field_1::$billez_code, Field_1::$times), 'function'),
																		 'from' => Table_1::$push_state,
																		 'join'=> $this->sql->join(array(Table_1::$bill), array(Table_1::$push_state . '.' . Field_1::$billez_code . ' = ' . Table_1::$bill . '.' . Field_1::$billez_code), array('')),
																		 'where' => $this->sql->where(array('where', 'like', 'where', 'where'), array($read, Table_1::$push_state . '.' . Field_1::$billez_code, Field_1::$year, Field_1::$month), array('n', $push_data['trader_code'] . $push_data['bill_kind_code'], $push_data['year'], $push_data['month']), array('', 'both', '', '')),
																		 'other' => '')), 'result_array');
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
										  'billez_code' => $data['billez_code'],
										  'result' => '',
										  'message' => ''));
			//更新推播狀態
			$this->sql->add_static(array('table'=> Table_1::$push_state,
										 'select'=> $this->sql->field(array(Field_2::$time, Field_1::$times, Field_1::$update_user, Field_1::$update_time), array($this->sql->get_time(1), $data['times'] + 1, $user['id'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where', 'where'), array(Field_1::$id, Field_1::$billez_code), array($data['id'], $data['billez_code']), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$push_state, '帳單推播_更新推播時間狀態', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$push_state, '帳單推播_更新推播時間狀態', $this->sql->get_time(1), '')),
										 'kind'=> 2));
		}
		
		//更新已推播帳單紀錄
		$this->sql->add_static(array('table'=> Table_1::$bill_import_log,
									 'select'=> $this->sql->field(array(Field_1::$pushed, Field_1::$update_user, Field_1::$update_time), array('y', $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where', 'where', 'where', 'where', 'where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_1::$year, Field_1::$month, Field_1::$import_bill_kind, Field_1::$batch_code), array($push_data['trader_code'], $push_data['bill_kind_code'], $push_data['year'], $push_data['month'], $push_data['import_bill_kind'], $push_data['batch_code']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$bill_import_log, '帳單推播_更新匯入帳單已推播狀態', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$bill_import_log, '帳單推播_更新匯入帳單已推播狀態', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			$route_data['sub_param'] = 1;
			$route_data['id'] = $user['id'];
			$this->push_model->bill_push($route_data, array('message' => $gcm, 'event' => $event, 'record' => '帳單推播_最新帳單推播通知', 'code' => '05'));
			return 0;
		} else {
			return 1;
		}
	}
	
	/*
	 * 查詢繳費帳單匯入紀錄列表
	 * $post	web傳來的參數
	 */
	public function search_import_pay_log($post) {
		if(strlen($post['trader']) > 4) $post['trader'] = '';
		if(strlen($post['bill_kind']) > 2) $post['bill_kind'] = '';
		
		//查詢繳費帳單匯入紀錄列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$batch_code), 'function'),
																		 'from' => Table_1::$bill_import_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$import_bill_kind), array(1)),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
	
		//查詢帳單列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$batch_code, Field_1::$year, Field_1::$month, Field_1::$import_time, Field_2::$file_name, 'IF (' . Field_1::$pushed . ' = "y", "是", "否") AS ' . Field_1::$pushed), 'function'),
																		 'from' => Table_1::$bill_import_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$import_bill_kind), array(1)),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		return $this->option->table($sql_result, array('批次碼', '年份', '月份', '匯入時間', '檔案名稱', '是否已推播'), base_url() . Param::$index_url . 'bill/import_pay_web') . $this->option->page($page_count, $post['page']);
	}
	
	/*
	 * 更新入帳帳單設定
	 * 使用逗號將開始和結束串接起來
	 * $post	參數
	 * $user	使用者
	 */
	public function update_receive_set($post, $user) {
		//更新入帳帳單設定
		$this->sql->add_static(array('table'=> Table_1::$trader_receive_bill_form,
									 'select'=> $this->sql->field(array(Field_1::$year, Field_1::$month, Field_1::$identify_data, Field_1::$bill_owner, Field_1::$data1, Field_1::$data2,Field_1::$data3, Field_1::$data4, Field_1::$data5, Field_1::$update_user, Field_1::$update_time),
											array($post['year'] . ',' . $post['year2'], $post['month'] . ',' . $post['month2'], $post['identify_data'] . ',' . $post['identify_data2'], $post['bill_owner'] . ',' . $post['bill_owner2'], $post['data1'] . ',' . $post['data12'], $post['data2'] . ',' . $post['data22'], $post['data3'] . ',' . $post['data32'], $post['data4'] . ',' . $post['data42'], $post['data5'] . ',' . $post['data52'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($post['trader'], $post['bill_kind']), array('', '')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$trader_receive_bill_form, '更新入帳帳單設定_更新入帳帳單格式設定', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$trader_receive_bill_form, '更新入帳帳單設定_更新入帳帳單格式設定', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'bill/update_receive_set_web';
		} else {
			return 2;
		}
	}
	
	/*
	 * 更新繳費帳單設定
	 * 使用逗號將開始和結束串接起來
	 * $post	參數
	 * $user	使用者
	 */
	public function update_pay_set($post, $user) {
		//更新繳費帳單設定
		$this->sql->add_static(array('table'=> Table_1::$trader_publish_bill_form,
									 'select'=> $this->sql->field(array(Field_1::$year, Field_1::$month, Field_1::$identify_data, Field_1::$bill_owner, Field_1::$data1, Field_1::$data2,Field_1::$data3, Field_1::$data4, Field_1::$data5, Field_2::$publish_time, Field_2::$due_time, Field_2::$amount, Field_2::$bank_charge, Field_2::$post_charge, Field_2::$cvs_charge, Field_2::$lowest_pay_amount, Field_2::$bank_barcode1, Field_2::$bank_barcode2, Field_2::$bank_barcode3, Field_2::$post_barcode1, Field_2::$post_barcode2, Field_2::$post_barcode3, Field_2::$cvs_barcode1, Field_2::$cvs_barcode2, Field_2::$cvs_barcode3, Field_4::$pay_place1, Field_4::$pay_place2, Field_4::$pay_place3, Field_4::$pay_place4, Field_4::$pay_place5, Field_4::$overdue_pay_place1, Field_4::$overdue_pay_place2, Field_1::$update_user, Field_1::$update_time),
																  array($post['year'] . ',' . $post['year2'], $post['month'] . ',' . $post['month2'], $post['identify_data'] . ',' . $post['identify_data2'], $post['bill_owner'] . ',' . $post['bill_owner2'], $post['data1'] . ',' . $post['data12'], $post['data2'] . ',' . $post['data22'], $post['data3'] . ',' . $post['data32'], $post['data4'] . ',' . $post['data42'], $post['data5'] . ',' . $post['data52'], $post['publish_time'] . ',' . $post['publish_time2'], $post['due_time'] . ',' . $post['due_time2'], $post['amount'] . ',' . $post['amount2'], $post['bank_charge'] . ',' . $post['bank_charge2'], $post['post_charge'] . ',' . $post['post_charge2'], $post['cvs_charge'] . ',' . $post['cvs_charge2'], $post['lowest_pay_amount'] . ',' . $post['lowest_pay_amount2'], $post['bank_barcode1'] . ',' . $post['bank_barcode12'], $post['bank_barcode2'] . ',' . $post['bank_barcode22'], $post['bank_barcode3'] . ',' . $post['bank_barcode32'], $post['post_barcode1'] . ',' . $post['post_barcode12'], $post['post_barcode2'] . ',' . $post['post_barcode22'], $post['post_barcode3'] . ',' . $post['post_barcode32'], $post['cvs_barcode1'] . ',' . $post['cvs_barcode12'], $post['cvs_barcode2'] . ',' . $post['cvs_barcode22'], $post['cvs_barcode3'] . ',' . $post['cvs_barcode32'], $post['pay_place1'] . ',' . $post['pay_place12'], $post['pay_place2'] . ',' . $post['pay_place22'], $post['pay_place3'] . ',' . $post['pay_place32'], $post['pay_place4'] . ',' . $post['pay_place42'], $post['pay_place5'] . ',' . $post['pay_place52'], $post['overdue_pay_place1'] . ',' . $post['overdue_pay_place12'], $post['overdue_pay_place2'] . ',' . $post['overdue_pay_place22'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($post['trader'], $post['bill_kind']), array('', '')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$trader_publish_bill_form, '更新繳費帳單設定_更新繳費帳單格式設定', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$trader_publish_bill_form, '更新繳費帳單設定_更新繳費帳單格式設定', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'bill/update_pay_set_web';
		} else {
			return 2;
		}
	}
	
	/*
	 * 查詢繳費帳單設定資料
 	 * $post	web傳來的參數
 	 * $table	資料表
	 */
	public function search_normal_set_data($post) {
		$post = explode(',', $post['id']);

		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('*'), ''),
																		 'from' => $post[2],
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($post[0], $post[1]), array('')),
																		 'other' => '')), 'row_array');
		if(isset($sql_result['trader_code'])) return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
		
		return 1;
	}
	
	/*
	 * 確認入帳帳單設定
	 * $post	參數
	 * $user	使用者
	 */
	public function check_receive_set($post, $user) {
		//檢查該設定是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$trader_code), ''),
				'from' => Table_1::$trader_receive_bill_form,
				'join'=> '',
				'where' => $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($post['trader'], $post['bill_kind']), array('')),
				'other' => '')), 'num_rows');
		if($sql_result) return 1;
	
		return $this->insert_receive_set($post, $user);
	}
	
	/*
	 * 新增入帳帳單設定
	 * 使用逗號將開始和結束串接起來
	 * $post	參數
	 * $user	使用者
	 */
	public function insert_receive_set($post, $user) {
	
		//新增入帳帳單設定
		$this->sql->add_static(array('table'=> Table_1::$trader_receive_bill_form,
									 'select'=> $this->sql->field(array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_1::$year, Field_1::$month, Field_1::$identify_data, Field_1::$bill_owner, Field_1::$data1, Field_1::$data2,Field_1::$data3, Field_1::$data4, Field_1::$data5, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time),
																 array($post['trader'], $post['bill_kind'], $post['year'] . ',' . $post['year2'], $post['month'] . ',' . $post['month2'], $post['identify_data'] . ',' . $post['identify_data2'], $post['bill_owner'] . ',' . $post['bill_owner2'], $post['data1'] . ',' . $post['data12'], $post['data2'] . ',' . $post['data22'], $post['data3'] . ',' . $post['data32'], $post['data4'] . ',' . $post['data42'], $post['data5'] . ',' . $post['data52'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$trader_receive_bill_form, '新增入帳帳單設定_新增入帳帳單格式設定', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$trader_receive_bill_form, '新增入帳帳單設定_新增入帳帳單格式設定', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'bill/insert_receive_set_web';
		} else {
			return 2;
		}
	}
	
	/*
	 * 確認繳費帳單設定
	 * $post	參數
	 * $user	使用者
	 */
	public function check_pay_set($post, $user) {
		//檢查該設定是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$trader_code), ''),
																		 'from' => Table_1::$trader_publish_bill_form,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($post['trader'], $post['bill_kind']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;
		
		return $this->insert_pay_set($post, $user);
	}
	
	/*
	 * 新增繳費帳單設定
	 * 使用逗號將開始和結束串接起來
	 * $post	參數
	 * $user	使用者
	 */
	public function insert_pay_set($post, $user) {
	
		//新增繳費帳單設定
		$this->sql->add_static(array('table'=> Table_1::$trader_publish_bill_form,
									 'select'=> $this->sql->field(array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_1::$year, Field_1::$month, Field_1::$identify_data, Field_1::$bill_owner, Field_1::$data1, Field_1::$data2,Field_1::$data3, Field_1::$data4, Field_1::$data5, Field_2::$publish_time, Field_2::$due_time, Field_2::$amount, Field_2::$bank_charge, Field_2::$post_charge, Field_2::$cvs_charge, Field_2::$lowest_pay_amount, Field_2::$bank_barcode1, Field_2::$bank_barcode2, Field_2::$bank_barcode3, Field_2::$post_barcode1, Field_2::$post_barcode2, Field_2::$post_barcode3, Field_2::$cvs_barcode1, Field_2::$cvs_barcode2, Field_2::$cvs_barcode3, Field_4::$pay_place1, Field_4::$pay_place2, Field_4::$pay_place3, Field_4::$pay_place4, Field_4::$pay_place5, Field_4::$overdue_pay_place1, Field_4::$overdue_pay_place2, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), 
																 array($post['trader'], $post['bill_kind'], $post['year'] . ',' . $post['year2'], $post['month'] . ',' . $post['month2'], $post['identify_data'] . ',' . $post['identify_data2'], $post['bill_owner'] . ',' . $post['bill_owner2'], $post['data1'] . ',' . $post['data12'], $post['data2'] . ',' . $post['data22'], $post['data3'] . ',' . $post['data32'], $post['data4'] . ',' . $post['data42'], $post['data5'] . ',' . $post['data52'], $post['publish_time'] . ',' . $post['publish_time2'], $post['due_time'] . ',' . $post['due_time2'], $post['amount'] . ',' . $post['amount2'], $post['bank_charge'] . ',' . $post['bank_charge2'], $post['post_charge'] . ',' . $post['post_charge2'], $post['cvs_charge'] . ',' . $post['cvs_charge2'], $post['lowest_pay_amount'] . ',' . $post['lowest_pay_amount2'], $post['bank_barcode1'] . ',' . $post['bank_barcode12'], $post['bank_barcode2'] . ',' . $post['bank_barcode22'], $post['bank_barcode3'] . ',' . $post['bank_barcode32'], $post['post_barcode1'] . ',' . $post['post_barcode12'], $post['post_barcode2'] . ',' . $post['post_barcode22'], $post['post_barcode3'] . ',' . $post['post_barcode32'], $post['cvs_barcode1'] . ',' . $post['cvs_barcode12'], $post['cvs_barcode2'] . ',' . $post['cvs_barcode22'], $post['cvs_barcode3'] . ',' . $post['cvs_barcode32'], $post['pay_place1'] . ',' . $post['pay_place12'], $post['pay_place2'] . ',' . $post['pay_place22'], $post['pay_place3'] . ',' . $post['pay_place32'], $post['pay_place4'] . ',' . $post['pay_place42'], $post['pay_place5'] . ',' . $post['pay_place52'], $post['overdue_pay_place1'] . ',' . $post['overdue_pay_place12'], $post['overdue_pay_place2'] . ',' . $post['overdue_pay_place22'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$trader_publish_bill_form, '新增繳費帳單設定_新增繳費帳單格式設定', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$trader_publish_bill_form, '新增繳費帳單設定_新增繳費帳單格式設定', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'bill/insert_pay_set_web';
		} else {
			return 2;
		}
	}
	
	/*
	 * 查詢帳單資料
	 * $post	web傳來的參數
	 */
	public function search_bill_data($post) {
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$batch_code, Field_1::$billez_code, Table_1::$trader_code . '.' . Field_1::$name . ' AS trader_name', Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind_name', Field_1::$year, Field_1::$month, Field_1::$identify_data, Field_1::$bill_owner, Field_1::$data1, Field_1::$data2, Field_1::$data3, Field_1::$data4, Field_1::$data5, Field_2::$publish_time, Field_2::$due_time, Field_2::$amount, Field_2::$lowest_pay_amount, Field_2::$bank_charge, Field_2::$post_charge, Field_2::$cvs_charge, Field_2::$bank_barcode1, Field_2::$bank_barcode2, Field_2::$bank_barcode3, Field_2::$post_barcode1, Field_2::$post_barcode2, Field_2::$post_barcode3, Field_2::$cvs_barcode1, Field_2::$cvs_barcode2, Field_2::$cvs_barcode3, 'IF(' . Field_2::$pay_state . ' = 1, "已繳費", "未繳費") AS ' . Field_2::$pay_state), 'function'),
																		 'from' => Table_1::$bill,
																		 'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code), array(Table_1::$bill . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_code . '.' . Field_1::$code, Table_1::$bill . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$bill_kind_code . '.' . Field_1::$code), array('', '')),
																		 'where' => $this->sql->where(array('where'), array(Field_1::$billez_code), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 查詢帳單列表
	 * $post	web傳來的參數
	 */
	public function search_bill($post) {
		if(strlen($post['trader']) > 4) $post['trader'] = '';
		if(strlen($post['bill_kind']) > 2) $post['bill_kind'] = '';
		if(strlen($post['year']) > 3) $post['year'] = '';
		if(strlen($post['month']) > 2) $post['month'] = '';

		//查詢帳單列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$billez_code), 'function'),
																		 'from' => Table_1::$bill,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$billez_code, Field_1::$trader_code, Field_1::$bill_kind_code, Field_1::$bill_owner, Field_1::$year, Field_1::$month, Field_1::$identify_data), array($post['billez_code'], $post['trader'], $post['bill_kind'], $post['owner'], $post['year'], $post['month'], $post['identify_data'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
		
		//查詢帳單列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$batch_code, Field_1::$billez_code . ' AS id', Table_1::$trader_code . '.' . Field_1::$name . ' AS trader_name', Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind_name', Field_1::$year, Field_1::$month, Field_1::$bill_owner, Field_1::$identify_data, Field_2::$publish_time, Field_2::$due_time, 'IF(' . Field_2::$pay_state . ' = 1, "已繳費", "未繳費") AS ' . Field_2::$pay_state), 'function'),
																		 'from' => Table_1::$bill,
																		 'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code), array(Table_1::$bill . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_code . '.' . Field_1::$code, Table_1::$bill . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$bill_kind_code . '.' . Field_1::$code), array('', '')),
																		 'where' => $this->sql->where_search(array(Field_1::$billez_code, Field_1::$trader_code, Field_1::$bill_kind_code, Field_1::$bill_owner, Field_1::$year, Field_1::$month, Field_1::$identify_data), array($post['billez_code'], $post['trader'], $post['bill_kind'], $post['owner'], $post['year'], $post['month'], $post['identify_data'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		return $this->option->table($sql_result, array('批次碼', '編號', '業者', '帳單種類', '年度', '月份', '所有人', '帳單辨識資料', '發行時間', '到期時間', '繳費狀態'), base_url() . Param::$index_url . 'bill/update_web') . $this->option->page($page_count, $post['page']);
	}

	/*
	 * 確認帳單依據
	 * $post	參數
	 * $user	使用者
	 */
	public function check_bill_basis($post, $user) {
		//檢查該名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$code), ''),
																		 'from' => Table_1::$bill_basis,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$name), array($post['name']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;
	
		return $this->insert_bill_basis($post, $user);
	}
	
	/*
	 * 新增帳單依據
	 * $post	參數
	 * $user	使用者
	 */
	public function insert_bill_basis($post, $user) {
		//查詢帳單依據最大代碼
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$code . ') AS max'), 'function'),
																		 'from' => Table_1::$bill_basis,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		$code = $this->create->code(2, $sql_result['max']);
	
		//新增帳單依據
		$this->sql->add_static(array('table'=> Table_1::$bill_basis,
									 'select'=> $this->sql->field(array(Field_1::$code, Field_1::$name, Field_1::$digit, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($code, $post['name'], $post['digit'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$bill_basis, '新增帳單依據_新增帳單依據名稱及碼數', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$bill_basis, '新增帳單依據_新增帳單依據名稱及碼數', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'bill/insert_basis_web';
		} else {
			return 2;
		}
	}
	
	/*
	 * 確認帳單種類
	 * $post	參數
	 * $user	使用者
	 */
	public function check_bill_kind($post, $user) {
		//檢查該名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$code), ''),
																		 'from' => Table_1::$bill_kind_code,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$name), array($post['name']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;
		
		return $this->insert_bill_kind($post, $user);
	}
	
	/*
	 * 新增帳單種類
	 * $post	參數
	 * $user	使用者
	 */
	public function insert_bill_kind($post, $user) {
		//查詢帳單種類最大代碼
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$code . ') AS max'), 'function'),
																		 'from' => Table_1::$bill_kind_code,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		$code = $this->create->code(2, $sql_result['max']);
		
		//新增帳單種類
		$this->sql->add_static(array('table'=> Table_1::$bill_kind_code,
									 'select'=> $this->sql->field(array(Field_1::$code, Field_1::$name, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($code, $post['name'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$bill_kind_code, '新增帳單種類_新增帳單種類名稱', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$bill_kind_code, '新增帳單種類_新增帳單種類名稱', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'bill/insert_kind_web';
		} else {
			return 2;
		}
	}
}