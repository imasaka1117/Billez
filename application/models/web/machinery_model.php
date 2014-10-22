<?php

class Machinery_model extends CI_Model {
	/*
	 * 確認更新代收機構合約資料
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function check_update_machinery_contract($post, $user) {
		//查詢要更換的代收機構合約名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$machinery_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id . ' !=', Field_1::$name), array($post['id'], $post['contract_name']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;
	
		return $this->update_machinery_contract($post, $user);
	}
	
	/*
	 * 更新代收機構合約資料
	* $post	web傳來的參數
	* $user	當前使用該系統者
	*/
	public function update_machinery_contract($post, $user) {
		//更新代收機構合約資料
		$this->sql->add_static(array('table'=> Table_1::$machinery_contract,
									 'select'=> $this->sql->field(array(Field_1::$name, Field_2::$age, Field_2::$begin, Field_2::$end, Field_2::$bill_cost_kind, Field_4::$month_rent_price, Field_2::$entity_price, Field_2::$action_price, Field_2::$pay, Field_3::$pay_week, Field_3::$pay_day, Field_3::$pay_month, Field_3::$remark, Field_1::$update_user, Field_1::$update_time),
																 array($post['contract_name'], $post['contract_age'], $post['begin_year'] . '/' .  $post['begin_month'] . '/' . $post['begin_day'], $post['end_year'] . '/' . $post['end_month'] . '/' . $post['end_day'], $post['bill_cost'], $post['month_rent_price'], $post['entity_price'], $post['action_price'], $post['pay'], $post['pay_week'], $post['pay_day'], $post['pay_month'], $post['contract_remark'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$machinery_contract, '更新代收機構合約_更新代收機構合約資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$machinery_contract, '更新代收機構合約_更新代收機構合約資料', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'machinery/search_contract_web';
		} else {
			return 2;
		}
	}
	
	/*
	 * 查詢代收機構合約資料
	* $post	web傳來的參數
	*/
	public function search_machinery_contract_data($post) {
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$machinery_code . '.' . Field_1::$name . ' AS machinery', Table_1::$machinery_contract . '.' . Field_1::$name . ' AS contract_name', Field_3::$ad_url, Field_2::$age . ' AS contract_age', 'YEAR(' . Field_2::$begin . ') AS begin_year', 'MONTH(' . Field_2::$begin . ') AS begin_month', 'DAY(' . Field_2::$begin . ') AS begin_day', 'YEAR(' . Field_2::$end . ') AS end_year', 'MONTH(' . Field_2::$end . ') AS end_month', 'DAY(' . Field_2::$end . ') AS end_day', Field_2::$bill_cost_kind . ' AS bill_cost', Field_4::$month_rent_price, Field_2::$entity_price, Field_2::$action_price, Field_2::$pay, Field_3::$pay_week, Field_3::$pay_month, Field_3::$pay_day, Field_3::$remark . ' AS contract_remark'), 'function'),
																		 'from' => Table_1::$machinery_contract,
																		 'join' => $this->sql->join(array(Table_1::$machinery_code), array(Table_1::$machinery_contract . '.' . Field_2::$machinery_code . ' = ' . Table_1::$machinery_code . '.' . Field_1::$code), array('')),
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
	
		return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 查詢代收機構合約列表
	* $post	web傳來的參數
	*/
	public function search_machinery_contract($post) {
		if(strlen($post['machinery_code']) > 2) $post['machinery_code'] = '';
	
		//查詢代收機構合約列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), 'function'),
																		 'from' => Table_1::$machinery_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name, Field_2::$machinery_code), array($post['id'], $post['name'], $post['machinery_code'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
	
		//查詢代收機構合約列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Table_1::$machinery_contract . '.' .Field_1::$name . ' AS ' . Field_1::$name, Table_1::$machinery_code . '.' . Field_1::$name . ' AS machinery_name', Field_2::$age, Field_2::$begin, Field_2::$end), 'function'),
																		 'from' => Table_1::$machinery_contract,
																		 'join'=> $this->sql->join(array(Table_1::$machinery_code), array(Table_1::$machinery_contract . '.' . Field_2::$machinery_code . ' = ' . Table_1::$machinery_code . '.' . Field_1::$code), array('')),
																		 'where' => $this->sql->where_search(array(Field_1::$id, Table_1::$machinery_contract . '.' .Field_1::$name, Field_2::$machinery_code), array($post['id'], $post['name'], $post['machinery_code'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		return $this->option->table($sql_result, array('合約編號', '合約名稱', '業者名稱', '合約年限', '合約開始日', '合約終止日'), base_url() . Param::$index_url . 'machinery/update_contract_web') . $this->option->page($page_count, $post['page']);
	}
	
	/*
	 * 確認更新代收機構資料
	* $post	web傳來的參數
	* $user	當前使用該系統者
	*/
	public function check_update_machinery($post, $user) {
		//查詢要更換的代收機構名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id . ' !=', Field_1::$name), array($post['id'], $post['name']), array('')),
																	 	 'other' => '')), 'num_rows');
		if($sql_result) return 1;
	
		//查詢要更換的統一編號是否重複
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$vat_number), ''),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id . ' !=', Field_3::$vat_number), array($post['id'], $post['vat_number']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 2;
	
		return $this->update_machinery($post, $user);
	}
	
	/*
	 * 更新代收機構資料
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function update_machinery($post, $user) {
		//查詢代收機構名稱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$name), ''),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		//更新代收機構資料
		$this->sql->add_static(array('table'=> Table_1::$machinery,
									 'select'=> $this->sql->field(array(Field_1::$name, Field_3::$telephone, Field_3::$level_code, Field_3::$vat_number, Field_1::$city, Field_1::$district, Field_3::$address, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email, Field_3::$second_contact_name, Field_3::$second_contact_phone, Field_3::$second_contact_email, Field_3::$remark, Field_1::$update_user, Field_1::$update_time),
											array($post['name'], $post['telephone'], $post['level_code'], $post['vat_number'], $post['city'], $post['district'], $post['address'], $post['main_contact_name'], $post['main_contact_phone'], $post['main_contact_email'], $post['second_contact_name'], $post['second_contact_phone'], $post['second_contact_email'], $post['remark'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$trader, '更新代收機構_更新代收機構資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$trader, '更新代收機構_更新代收機構資料', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//更新代收機構名稱
		$this->sql->add_static(array('table'=> Table_1::$machinery_code,
									 'select'=> $this->sql->field(array(Field_1::$name, Field_1::$update_user, Field_1::$update_time), array($post['name'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$name), array($sql_result['name']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$machinery_code, '更新代收機構_更新代收機構名稱', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$machinery_code, '更新代收機構_更新代收機構名稱', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'machinery/search_web';
		} else {
			return 3;
		}
	}
	
	/*
	 * 查詢業者資料
	* $post	web傳來的參數
	*/
	public function search_machinery_data($post) {
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$name, Field_3::$telephone, Field_3::$level_code, Field_3::$vat_number, Field_1::$city, Field_1::$district, Field_3::$address, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email, Field_3::$second_contact_name, Field_3::$second_contact_phone, Field_3::$second_contact_email, Field_3::$remark), ''),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 查詢代收機構列表
	 * $post	web傳來的參數
	 */
	public function search_machinery($post) {
		if(strlen($post['level_code']) > 1) $post['level_code'] = '';
	
		//查詢代收機構列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), 'function'),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name, Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$level_code), array($post['id'], $post['name'], $post['telephone'], $post['vat_number'], $post['main_contact_name'], $post['level_code'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
		//查詢代收機構列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Field_1::$name, 'CONCAT(' . Field_1::$city . ',' . Field_1::$district . ',' . Field_3::$address . ') AS address ', Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email), 'function'),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name, Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$level_code), array($post['id'], $post['name'], $post['telephone'], $post['vat_number'], $post['main_contact_name'], $post['level_code'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		return $this->option->table($sql_result, array('編號', '名稱', '地址', '電話', '統一編號', '主要聯絡人名稱', '主要聯絡人電話', '主要聯絡人電子郵件'), base_url() . Param::$index_url . 'machinery/update_web') . $this->option->page($page_count, $post['page']);
	}
	
	/*
	 * 檢查新增代收機構名稱
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function check_machinery($post, $user) {
		//查詢代收機構名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$name), array($post['name']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;
		
		//查詢統一編號是否重複
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$vat_number), ''),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_3::$vat_number), array($post['vat_number']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 2;
		
		return $this->insert_machinery($post, $user);
	}
	
	/*
	 * 新增代收機構資料
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function insert_machinery($post, $user) {
		//查詢代收機構最大編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$id . ') AS max'), 'function'),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//產生代收機構編號
		$id = $this->create->id('MA', $sql_result['max']);
	
		//查詢最大代收機構代碼編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$code . ') AS max'), 'function'),
																		 'from' => Table_1::$machinery_code,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//產生代收機構代馬
		$code = $this->create->code(2, $sql_result['max']);
	
		//新增代收機構資料
		$this->sql->add_static(array('table'=> Table_1::$machinery,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$name, Field_1::$city, Field_1::$district, Field_3::$address, Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email, Field_3::$second_contact_name, Field_3::$second_contact_phone, Field_3::$second_contact_email, Field_3::$level_code, Field_3::$remark, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time),
											array($id, $post['name'], $post['city'], $post['district'], $post['address'], $post['telephone'], $post['vat_number'], $post['main_contact_name'], $post['main_contact_phone'], $post['main_contact_email'], $post['second_contact_name'], $post['second_contact_phone'], $post['second_contact_email'], $post['level_code'], $post['remark'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$machinery, '新增代收機構_創建代收機構資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$machinery, '新增代收機構_創建代收機構資料', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//新增代收機構代碼
		$this->sql->add_static(array('table'=> Table_1::$machinery_code,
									 'select'=> $this->sql->field(array(Field_1::$code, Field_1::$name, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($code, $post['name'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$machinery_code, '新增代收機構_新增代收機構代碼', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$machinery_code, '新增代收機構_新增代收機構代碼', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'machinery/insert_web';
		} else {
			return 3;
		}
	}
	
	/*
	 * 檢查代收機構合約名稱
	* $post	web傳來的參數
	* $user	當前使用該系統者
	*/
	public function check_machinery_contract($post, $user) {
		//查詢代收機構合約名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$machinery_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$name, Field_1::$id . ' !='), array($post['contract_name'], $post['machinery']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;
	
		return $this->insert_machinery_contract($post, $user);
	}
	
	/*
	 * 新增代收機構合約
	* $post	web傳來的參數
	* $user	當前使用該系統者
	*/
	public function insert_machinery_contract($post, $user) {
		//查詢代收機構合約最大編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$id . ') AS max'), 'function'),
																		 'from' => Table_1::$machinery_contract,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//產生代收機構合約編號
		$id = $this->create->id('MC', $sql_result['max']);
	
		//新增代收機構合約
		$this->sql->add_static(array('table'=> Table_1::$machinery_contract,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$name, Field_2::$machinery_code, Field_2::$age, Field_2::$begin, Field_2::$end, Field_2::$bill_cost_kind, Field_4::$month_rent_price, Field_2::$entity_price, Field_2::$action_price, Field_2::$pay, Field_3::$pay_week, Field_3::$pay_month, Field_3::$pay_day, Field_3::$ad_url, Field_3::$remark, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time),
																 array($id, $post['contract_name'], $post['machinery'], $post['contract_age'], $post['begin_year'] . '/' .  $post['begin_month'] . '/' . $post['begin_day'], $post['end_year'] . '/' . $post['end_month'] . '/' . $post['end_day'], $post['bill_cost'], $post['month_rent_price'], $post['entity_price'], $post['action_price'], $post['pay'], $post['pay_week'], $post['pay_month'], $post['pay_day'], $post['ad_url'], $post['contract_remark'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$machinery_contract, '新增代收機構合約_新增代收機構合約資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$machinery_contract, '新增代收機構合約_新增代收機構合約資料', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'machinery/insert_contract_web';
		} else {
			return 2;
		}
	}
}