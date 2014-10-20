<?php

class Trader_model extends CI_Model {
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
		if(!$sql_result) return 1;
		
		//查詢統一編號是否重複
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$vat_number), ''),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_3::$vat_number), array($post['vat_number']), array('')),
																		 'other' => '')), 'num_rows');
		if(!$sql_result) return 2;
		
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
	
}//end