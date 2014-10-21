<?php

class Trader_model extends CI_Model {
	/*
	 * 確認更新業者資料
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function check_update_trader($post, $user) {
		//查詢要更換的業者名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id . ' !=', Field_1::$name), array($post['id'], $post['name']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;
		
		//查詢要更換的統一編號是否重複
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$vat_number), ''),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id . ' !=', Field_3::$vat_number), array($post['id'], $post['vat_number']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 2;
		
		return $this->update_trader($post, $user);
	}
	
	/*
	 * 更新業者資料
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function update_trader($post, $user) {
		//查詢業者名稱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$name), ''),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		//更新業者資料
		$this->sql->add_static(array('table'=> Table_1::$trader,
									 'select'=> $this->sql->field(array(Field_1::$name, Field_3::$telephone, Field_3::$level_code, Field_3::$vat_number, Field_1::$city, Field_1::$district, Field_3::$address, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email, Field_3::$second_contact_name, Field_3::$second_contact_phone, Field_3::$second_contact_email, Field_3::$remark, Field_1::$update_user, Field_1::$update_time), 
																 array($post['name'], $post['telephone'], $post['level_code'], $post['vat_number'], $post['city'], $post['district'], $post['address'], $post['main_contact_name'], $post['main_contact_phone'], $post['main_contact_email'], $post['second_contact_name'], $post['second_contact_phone'], $post['second_contact_email'], $post['remark'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$trader, '更新業者_更新業者資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$trader, '更新業者_更新業者資料', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//更新業者名稱
		$this->sql->add_static(array('table'=> Table_1::$trader_code,
									 'select'=> $this->sql->field(array(Field_1::$name, Field_1::$update_user, Field_1::$update_time), array($post['name'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$name), array($sql_result['name']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$trader_code, '更新業者_更新業者名稱', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$trader_code, '更新業者_更新業者名稱', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'trader/search_web';
		} else {
			return 3;
		}
	}
	
	/*
	 * 查詢業者資料
	 * $post	web傳來的參數
	 */
	public function search_trader_data($post) {
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$name, Field_3::$telephone, Field_3::$level_code, Field_3::$vat_number, Field_1::$city, Field_1::$district, Field_3::$address, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email, Field_3::$second_contact_name, Field_3::$second_contact_phone, Field_3::$second_contact_email, Field_3::$remark), ''),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 查詢業者列表
	 * $post	web傳來的參數
	 */
	public function search_trader($post) {
		if(strlen($post['level_code']) > 1) $post['level_code'] = '';
		
		//查詢業者列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Field_1::$name, 'CONCAT(' . Field_1::$city . ',' . Field_1::$district . ',' . Field_3::$address . ') AS address ', Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email), 'function'),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name, Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$level_code), array($post['id'], $post['name'], $post['telephone'], $post['vat_number'], $post['main_contact_name'], $post['level_code'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
		//查詢業者列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Field_1::$name, 'CONCAT(' . Field_1::$city . ',' . Field_1::$district . ',' . Field_3::$address . ') AS address ', Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email), 'function'),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name, Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$level_code), array($post['id'], $post['name'], $post['telephone'], $post['vat_number'], $post['main_contact_name'], $post['level_code'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		return $this->option->table($sql_result, array('編號', '名稱', '地址', '電話', '統一編號', '主要聯絡人名稱', '主要聯絡人電話', '主要聯絡人電子郵件'), base_url() . Param::$index_url . 'trader/update_web') . $this->option->page($page_count, $post['page'], 'search_trader_num');
	}
	
	/*
	 * 檢查是否已有相同的業者或統一編號
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function check_trader($post, $user) {
		//查詢業者名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$name), array($post['name']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;
		
		//查詢統一編號是否重複
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$vat_number), ''),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_3::$vat_number), array($post['vat_number']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 2;
		
		return $this->insert_trader($post, $user);
	}
	
	/*
	 * 新增業者資料
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function insert_trader($post, $user) {
		//查詢業者最大編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$id . ') AS max'), 'function'),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//產生業者編號
		$id = $this->create->id('TR', $sql_result['max']);
		
		//查詢最大業者代碼編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$code . ') AS max'), 'function'),
																		 'from' => Table_1::$trader_code,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//產生業者代馬
		$code = $this->create->code(4, $sql_result['max']);
		
		//新增業者資料
		$this->sql->add_static(array('table'=> Table_1::$trader,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$name, Field_1::$city, Field_1::$district, Field_3::$address, Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email, Field_3::$second_contact_name, Field_3::$second_contact_phone, Field_3::$second_contact_email, Field_3::$level_code, Field_3::$remark, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time),
											 					 array($id, $post['name'], $post['city'], $post['district'], $post['address'], $post['telephone'], $post['vat_number'], $post['main_contact_name'], $post['main_contact_phone'], $post['main_contact_email'], $post['second_contact_name'], $post['second_contact_phone'], $post['second_contact_email'], $post['level_code'], $post['remark'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$trader, '新增業者_創建業者資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$trader, '新增業者_創建業者資料', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//新增業者代碼
		$this->sql->add_static(array('table'=> Table_1::$trader_code,
									 'select'=> $this->sql->field(array(Field_1::$code, Field_1::$name, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($code, $post['name'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$trader_code, '新增業者_新增業者代碼', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$trader_code, '新增業者_新增業者代碼', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'trader/insert_web';
		} else {
			return 3;
		}
	}
	
	/*
	 * 檢查業者合約名稱
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function check_trader_contract($post, $user) {
		//查詢業者合約名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$trader_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where', 'where'), array(Field_1::$name, Field_1::$trader_code, Field_1::$bill_kind_code), array($post['contract_name'], $post['trader'], $post['bill_kind']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;

		return $this->insert_trader_contract($post, $user);
	}
	
	/*
	 * 新增業者合約
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function insert_trader_contract($post, $user) {
		if(strlen($post['machinery']) == 2) {
			//新增業者代收機構
			$this->sql->add_static(array('table'=> Table_1::$trader_machinery,
										 'select'=> $this->sql->field(array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_2::$machinery_code, Field_4::$machinery_contract, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($post['trader'], $post['bill_kind'], $post['machinery'], $post['machinery_contract'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
										 'where'=> '',
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$trader_machinery, '新增業者合約_新增業者代收機構', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$trader_machinery, '新增業者合約_新增業者代收機構', $this->sql->get_time(1), '')),
										 'kind'=> 1));	
		}
		
		//查詢業者合約最搭編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$id . ') AS max'), 'function'),
																		 'from' => Table_1::$trader_contract,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//產生業者合約編號
		$id = $this->create->id('TC', $sql_result['max']);
		
		//新增業者合約
		$this->sql->add_static(array('table'=> Table_1::$trader_contract,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$name, Field_1::$trader_code, Field_1::$bill_kind_code, Field_2::$age, Field_2::$begin, Field_2::$end, Field_4::$publish, Field_4::$publish_week, Field_4::$publish_day, Field_4::$publish_month, Field_4::$enter, Field_4::$enter_week, Field_4::$enter_day, Field_4::$enter_month, Field_4::$collection, Field_4::$collection_week, Field_4::$collection_day, Field_4::$collection_month, Field_4::$bill_price_kind, Field_4::$month_rent_price, Field_2::$entity_price, Field_2::$action_price, Field_3::$send_condition, Field_4::$send_condition_times, Field_4::$contract_remark, Field_4::$email_send, Field_4::$email_publish, Field_4::$email_publish_week, Field_4::$email_publish_day, Field_4::$email_publish_month, Field_4::$ftp_ip, Field_4::$ftp_account, Field_4::$ftp_password, Field_4::$ftp_path, Field_4::$ftp_receive_path, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), 
																  array($id, $post['contract_name'], $post['trader'], $post['bill_kind'], $post['contract_age'], $post['begin_year'] . '/' .  $post['begin_month'] . '/' . $post['begin_day'], $post['end_year'] . '/' . $post['end_month'] . '/' . $post['end_day'], $post['publish'], $post['publish_week'], $post['publish_day'], $post['publish_month'], $post['enter'], $post['enter_week'], $post['enter_day'], $post['enter_month'], $post['collection'], $post['collection_week'], $post['collection_day'], $post['collection_month'], $post['bill_price'], $post['month_rent_price'], $post['entity_price'], $post['action_price'], $post['send_condition'], $post['send_condition_times'], $post['contract_remark'], $post['send_email'], $post['email_publish'], $post['email_publish_week'], $post['email_publish_day'], $post['email_publish_month'], $post['ftp_ip'], $post['ftp_account'], $post['ftp_password'], $post['ftp_path'], $post['ftp_receive_path'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$trader_contract, '新增業者合約_新增業者合約資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$trader_contract, '新增業者合約_新增業者合約資料', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//查詢帳單依據碼數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$digit), ''),
																		 'from' => Table_1::$bill_basis,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$code), array($post['bill_basis']), array('')),
																		 'other' => '')), 'row_array');
		$bill_digit_number = $sql_result['digit'];
		
		//新增業者帳單
		$this->sql->add_static(array('table'=> Table_1::$trader_bill,
									 'select'=> $this->sql->field(array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_4::$bill_basis_code, Field_4::$bill_digit_number, Field_4::$bill_ad_url, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($post['trader'], $post['bill_kind'], $post['bill_basis'], $bill_digit_number, $post['ad_url'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$trader_bill, '新增業者合約_新增業者帳單', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$trader_bill, '新增業者合約_新增業者帳單', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'trader/insert_contract_web';
		} else {
			return 2;
		}
	}
}//end